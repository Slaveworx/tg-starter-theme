#!/usr/bin/env node

import { Command } from "commander";
import inquirer from "inquirer";
import fs from "fs";
import handlebars from "handlebars";

const program = new Command();

const templateNames = {
  component: ["component-php.hbs", "component-scss.hbs", "component-js.hbs"],
  archive: ["archive-php.hbs", "archive-scss.hbs", "archive-js.hbs"],
  page: ["page-php.hbs", "page-scss.hbs", "page-js.hbs"],
  single: ["single-php.hbs", "single-scss.hbs", "single-js.hbs"],
};

const createTemplate = (type, targetDir, pageInfo) => {
  const [phpTemplateName, scssTemplateName, jsTemplateName] = templateNames[type];
  fs.mkdirSync(targetDir);

  ["php", "scss", "js"].forEach((ext, idx) => {
    const templateName = [phpTemplateName, scssTemplateName, jsTemplateName][idx];
    const templateSource = fs.readFileSync(`./config/sources/${type}/${templateName}`, "utf8");
    const template = handlebars.compile(templateSource);
    const renderedTemplate = template(pageInfo);

    const fileName = type === "component" ? `${pageInfo.fileName}.${ext}` : `${type}-${pageInfo.fileName}.${ext}`;
    fs.writeFileSync(`${targetDir}/${fileName}`, renderedTemplate);
  });

  const mainScssPath = "./src/scss/main.scss";
  const importStatement = `@import './${targetDir.replace("./", "")}/${type === "component" ? "" : type + "-"}${pageInfo.fileName}';\n`;
  fs.appendFileSync(mainScssPath, importStatement);

  console.log(`${type[0].toUpperCase() + type.slice(1)} Template ${pageInfo.fileName} generated successfully!`);
};

function promptName(inputName, message) {
  if (inputName) {
    return Promise.resolve({ singleName: inputName });
  } else {
    return inquirer.prompt([
      {
        type: "input",
        name: "singleName",
        message: message,
        validate: function (value) {
          if (value.length) {
            return true;
          } else {
            return "Please enter a valid name.";
          }
        },
      },
    ]);
  }
}

// GENERATE COMPONENT
program
  .command("generate:component [componentName]")
  .description("Generate a new component")
  .action((componentName) => {
    promptName(
      componentName,
      "What is the name of your component (example: testimonials-block)?"
    ).then(({ singleName }) =>
      createTemplate("component", `./components/${singleName}`, { fileName: singleName, componentName: singleName })
    );
  });

// GENERATE ARCHIVE TEMPLATES
program
  .command("generate:archive [archiveName]")
  .description("Generate a new archive template")
  .action((archiveName) => {
    promptName(
      archiveName,
      "What is the name of your post type (example: post)?"
    ).then(({ singleName }) =>
      createTemplate("archive", `./template-archives/${singleName}`, { fileName: singleName, archiveName: singleName })
    );
  });

// GENERATE PAGE TEMPLATE
program.command("generate:page [pageName] [fileName]")
  .description("Generate a new page template")
  .action((pageName, fileName) => inquirer.prompt([
    { type: "input", name: "pageName", message: "What is the name of your page template (example: Contact Us Page)?", default: pageName, when: () => !pageName },
    { type: "input", name: "fileName", message: "What is the name for the file (example: contact-us)?", default: fileName, when: () => !fileName },
  ]).then(({ pageName, fileName }) => createTemplate("page", `./template-pages/${fileName}`, { fileName, pageName })));

  
// GENERATE SINGLE TEMPLATES
program
  .command("generate:single [singleName]")
  .description("Generate a new single template")
  .action((singleName) => {
    promptName(
      singleName,
      "What is the name of your post type (example: post)?"
    ).then(({ singleName }) =>
      createTemplate("single", `./template-singles/${singleName}`, { fileName: singleName, singleName: singleName })
    );
  });

program.parse(process.argv);
