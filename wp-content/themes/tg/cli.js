#!/usr/bin/env node

import { Command } from 'commander';
import inquirer from 'inquirer';
import fs from 'fs';
import handlebars from 'handlebars';

const program = new Command();

const phpTemplateName = 'component-php.hbs';
const scssTemplateName = 'component-scss.hbs';

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

      // Read in the PHP template and compile it
      const phpTemplateSource = fs.readFileSync(`./config/sources/${phpTemplateName}`, 'utf8');
      const phpTemplate = handlebars.compile(phpTemplateSource);

      // Render the PHP template
      const renderedPHPTemplate = phpTemplate({ componentName: componentNameValue });
      fs.writeFileSync(`./components/${componentNameValue}.php`, renderedPHPTemplate);

      // Read in the SCSS template and compile it
      const scssTemplateSource = fs.readFileSync(`./config/sources/${scssTemplateName}`, 'utf8');
      const scssTemplate = handlebars.compile(scssTemplateSource);

      // Render the SCSS template
      const renderedSCSSTemplate = scssTemplate({ componentName: componentNameValue });
      fs.writeFileSync(`./src/scss/components/${componentNameValue}.scss`, renderedSCSSTemplate);

      // Append import statement to main.scss file
      const mainScssPath = './src/scss/main.scss';
      const importStatement = `@import 'components/${componentNameValue}';\n`;
      fs.appendFileSync(mainScssPath, importStatement);

      console.log(`Component ${componentNameValue} generated successfully!`);
    });
  });

program.parse(process.argv);
