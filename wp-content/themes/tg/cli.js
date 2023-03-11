#!/usr/bin/env node

import { Command } from 'commander';
import inquirer from 'inquirer';
import fs from 'fs';
import handlebars from 'handlebars';

const program = new Command();

const phpTemplateName = 'component-php.hbs';
const scssTemplateName = 'component-scss.hbs';
const jsTemplateName = 'component-js.hbs';

program
  .command('generate <componentName>')
  .description('Generate a new component')
  .action((componentName) => {
    let componentNameValue = componentName;

    const componentNameQuestion = {
      type: 'input',
      name: 'componentName',
      message: 'What is the name of your component?',
      default: componentNameValue,
      when: () => !componentNameValue
    };

    inquirer.prompt(componentNameQuestion).then((answers) => {
      componentNameValue = componentNameValue || answers.componentName;

      // Create a directory for the new component
      fs.mkdirSync(`./components/${componentNameValue}`);

      // Read in the PHP template and compile it
      const phpTemplateSource = fs.readFileSync(`./config/sources/${phpTemplateName}`, 'utf8');
      const phpTemplate = handlebars.compile(phpTemplateSource);

      // Render the PHP template
      const renderedPHPTemplate = phpTemplate({ componentName: componentNameValue });
      fs.writeFileSync(`./components/${componentNameValue}/${componentNameValue}.php`, renderedPHPTemplate);

      // Read in the SCSS template and compile it
      const scssTemplateSource = fs.readFileSync(`./config/sources/${scssTemplateName}`, 'utf8');
      const scssTemplate = handlebars.compile(scssTemplateSource);

      // Render the SCSS template
      const renderedSCSSTemplate = scssTemplate({ componentName: componentNameValue });
      fs.writeFileSync(`./components/${componentNameValue}/${componentNameValue}.scss`, renderedSCSSTemplate);

      // Read in the JS template and compile it
      const jsTemplateSource = fs.readFileSync(`./config/sources/${jsTemplateName}`, 'utf8');
      const jsTemplate = handlebars.compile(jsTemplateSource);

      // Render the JS template
      const renderedJsTemplate = jsTemplate({ componentName: componentNameValue });
      fs.writeFileSync(`./components/${componentNameValue}/${componentNameValue}.js`, renderedJsTemplate);

      // Append import statement to main.scss file
      const mainScssPath = './src/scss/main.scss';
      const importStatement = `@import './components/${componentNameValue}/${componentNameValue}';\n`;
      fs.appendFileSync(mainScssPath, importStatement);

      console.log(`Component ${componentNameValue} generated successfully!`);
    });
  });

program.parse(process.argv);
