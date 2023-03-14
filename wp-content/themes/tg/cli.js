#!/usr/bin/env node

import { Command } from "commander";
import inquirer from "inquirer";
import fs from "fs";
import handlebars from "handlebars";

const program = new Command();

const phpTemplateName = "component-php.hbs";
const scssTemplateName = "component-scss.hbs";
const jsTemplateName = "component-js.hbs";

// GENERATE COMPONENT
program
  .command("generate-component [componentName]")
  .description("Generate a new component")
  .action((componentName) => {
    let componentNameValue = componentName;

    const componentNameQuestion = {
      type: "input",
      name: "componentName",
      message:
        "What is the name of your component (example: testimonials-block)?",
      default: componentNameValue,
      when: () => !componentNameValue,
    };

    inquirer.prompt(componentNameQuestion).then((answers) => {
      componentNameValue = componentNameValue || answers.componentName;

      // Create a directory for the new component
      fs.mkdirSync(`./components/${componentNameValue}`);

      // Read in the PHP template and compile it
      const phpTemplateSource = fs.readFileSync(
        `./config/sources/component/${phpTemplateName}`,
        "utf8"
      );
      const phpTemplate = handlebars.compile(phpTemplateSource);

      // Render the PHP template
      const renderedPHPTemplate = phpTemplate({
        componentName: componentNameValue,
      });
      fs.writeFileSync(
        `./components/${componentNameValue}/${componentNameValue}.php`,
        renderedPHPTemplate
      );

      // Read in the SCSS template and compile it
      const scssTemplateSource = fs.readFileSync(
        `./config/sources/component/${scssTemplateName}`,
        "utf8"
      );
      const scssTemplate = handlebars.compile(scssTemplateSource);

      // Render the SCSS template
      const renderedSCSSTemplate = scssTemplate({
        componentName: componentNameValue,
      });
      fs.writeFileSync(
        `./components/${componentNameValue}/${componentNameValue}.scss`,
        renderedSCSSTemplate
      );

      // Read in the JS template and compile it
      const jsTemplateSource = fs.readFileSync(
        `./config/sources/component/${jsTemplateName}`,
        "utf8"
      );
      const jsTemplate = handlebars.compile(jsTemplateSource);

      // Render the JS template
      const renderedJsTemplate = jsTemplate({
        componentName: componentNameValue,
      });
      fs.writeFileSync(
        `./components/${componentNameValue}/${componentNameValue}.js`,
        renderedJsTemplate
      );

      // Append import statement to main.scss file
      const mainScssPath = "./src/scss/main.scss";
      const importStatement = `@import './components/${componentNameValue}/${componentNameValue}';\n`;
      fs.appendFileSync(mainScssPath, importStatement);

      console.log(`Component ${componentNameValue} generated successfully!`);
    });
  });


const pagePhpTemplateName = "page-php.hbs";
const pageScssTemplateName = "page-scss.hbs";
const pageJsTemplateName = "page-js.hbs";

// GENERATE PAGE TEMPLATE
program
  .command("generate-page [pageName] [fileName]")
  .description("Generate a new page template")
  .action((args) => {
    let pageNameValue = "";
    let fileNameValue = "";

    if (args) {
      const { pageName, fileName } = args;
      pageNameValue = pageName || "";
      fileNameValue = fileName || "";
    }

    const pageNameQuestion = {
      type: "input",
      name: "pageName",
      message:
        "What is the name of your page template (example: Contact Us Page)?",
      default: pageNameValue,
      when: () => !pageNameValue,
    };

    const fileNameQuestion = {
      type: "input",
      name: "fileName",
      message: "What is the name for the file (example: contact-us)?",
      default: fileNameValue,
      when: () => !fileNameValue,
    };

    inquirer.prompt([pageNameQuestion, fileNameQuestion]).then((answers) => {
      pageNameValue = pageNameValue || answers.pageName;
      fileNameValue = fileNameValue || answers.fileName;

      // Create a directory for the new page
      fs.mkdirSync(`./template-pages/${fileNameValue}`);

      // Read in the PHP template and compile it
      const phpTemplateSource = fs.readFileSync(
        `./config/sources/page/${pagePhpTemplateName}`,
        "utf8"
      );
      const phpTemplate = handlebars.compile(phpTemplateSource);

      // Render the PHP template
      const renderedPHPTemplate = phpTemplate({ pageName: pageNameValue, fileName: fileNameValue });
      fs.writeFileSync(
        `./template-pages/${fileNameValue}/page-${fileNameValue}.php`,
        renderedPHPTemplate
      );

      // Read in the SCSS template and compile it
      const scssTemplateSource = fs.readFileSync(
        `./config/sources/page/${pageScssTemplateName}`,
        "utf8"
      );
      const scssTemplate = handlebars.compile(scssTemplateSource);

      // Render the SCSS template
      const renderedSCSSTemplate = scssTemplate({ pageName: pageNameValue, fileName: fileNameValue });
      fs.writeFileSync(
        `./template-pages/${fileNameValue}/page-${fileNameValue}.scss`,
        renderedSCSSTemplate
      );

      // Read in the JS template and compile it
      const jsTemplateSource = fs.readFileSync(
        `./config/sources/page/${pageJsTemplateName}`,
        "utf8"
      );
      const jsTemplate = handlebars.compile(jsTemplateSource);

      // Render the JS template
      const renderedJsTemplate = jsTemplate({ pageName: pageNameValue, fileName: fileNameValue});
      fs.writeFileSync(
        `./template-pages/${fileNameValue}/page-${fileNameValue}.js`,
        renderedJsTemplate
      );

      // Append import statement to main.scss file
      const mainScssPath = "./src/scss/main.scss";
      const importStatement = `@import './template-pages/${fileNameValue}/${fileNameValue}';\n`;
      fs.appendFileSync(mainScssPath, importStatement);

      console.log(`Page Template ${pageNameValue} generated successfully!`);
    });
  });


program.parse(process.argv);
