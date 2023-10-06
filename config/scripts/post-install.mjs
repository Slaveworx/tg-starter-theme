import fs from 'fs/promises';
import inquirer from 'inquirer';
import chalk from 'chalk';
import path from 'path';
import { fileURLToPath } from 'url';


const __dirname = path.dirname(fileURLToPath(import.meta.url));
const flagFilePath = path.resolve(__dirname, '.postinstall');

async function createEnvFile(devUrl) {
    try {
        const envContent = `LOCAL_SITE=${devUrl}`;
        await fs.writeFile(path.resolve('.env'), envContent, 'utf8');
        console.log(chalk.green('Successfully created .env file!'));
    } catch (error) {
        console.error(chalk.red('Error creating .env file:'), error);
    }
}


async function postInstall() {

    const questionsAndDefaults = [
        { name: 'dev_url', message: 'Enter Development Server URL', default: 'tg.local' },
    ];

    console.log(
        chalk.red.bold.bgWhite('####################### TG STARTER THEME #######################\n') +
        chalk.black.bgGreen('            Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ 𝑣𝑒𝑟𝑠𝑖𝑜𝑛 3.0.0               \n') +
        chalk.black.bgGreen('                    Author: Tiago M. Galvao                     \n') +
        chalk.red.bold.bgWhite('################################################################\n')
    );

    console.log(chalk.red.bold('Press ENTER key to accept defaults'))

    try {
        if (process.stdout.isTTY) {
            // Run interactive code
            const answers = await inquirer.prompt(questionsAndDefaults);
            await createEnvFile(answers.dev_url);
        } else {
            // Handle non-interactive case
            console.log(chalk.yellow('Non-interactive shell detected, skipping input.'));
            await createEnvFile(questionsAndDefaults[0].default);
        }
        console.log(chalk.whiteBright.bgGreenBright.bold('\n\nINITIAL CONFIGURATION WAS COMPLETED SUCCESSFULLY!\n\n'));
    } catch (error) {
        console.error(chalk.red('Error:'), error);
    }
}

async function runPostInstall() {
    try {
        await fs.access(flagFilePath);
        console.log(chalk.yellow.bold('The postinstall script has already been run. Skipping.'));
    } catch (error) {
        await postInstall();
        await fs.writeFile(flagFilePath, 'This file indicates that the postinstall script has been run', 'utf8');
        console.log(chalk.green.bold('The postinstall script has run successfully. It will not run again.'));
    }
}

runPostInstall();
