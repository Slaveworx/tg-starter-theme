<div align="center">

![WordPress Version](https://img.shields.io/wordpress/v/akismet.svg?style=flat-square) ![GitHub release](https://img.shields.io/github/release/slaveworx/tg-starter-theme.svg?style=flat-square) ![GitHub](https://img.shields.io/github/license/slaveworx/tg-starter-theme.svg?style=flat-square)

# TG Wordpress Starter Theme

</div>

The future of WordPress development that combines elegance, efficiency, and cutting-edge technology. Experience unparalleled convenience, performance, and organization while creating stunning websites that rank at the top of search engine results.

## :sparkles: Key Features :sparkles:

- :file_cabinet: **Highly organized and segregated structure** for maximum code reusability.
- :wrench: **Powerful helper functions** to streamline verbose and redundant processes.
- :art: **Smart rendering mechanism** that enqueues styles and scripts only when needed.
- :gear: **Advanced dependency management system** for precise control over components.
- :rocket: **Cutting-edge automation mechanisms** for generating components, pages, archives, and singles.
- :computer: **Command Line Interface (CLI)** for smooth and efficient project management.
- :zap: **On-the-fly compilation and minification** of SASS and JavaScript for optimal performance.
- :eyes: **Built-in development server** with live reload for a seamless development experience.
- :trophy: **Follows SEO and development best practices** to maximize search engine rankings.
- :100: **100% Google PageSpeed Insights score** for lightning-fast load times and enhanced user experience.
- :shopping_cart: **WooCommerce support** for seamless integration with the popular e-commerce platform.
- :cloud: **Built-in cache system** to optimize performance and reduce server load.
- :gem: **Font optimization system** to ensure fast and efficient font loading.


Unlock the full potential of your website with TG Wordpress Starter Theme. Start creating remarkable, high-performance websites today and elevate your web development journey to new heights.

📖 [Read our Documentation](https://github.com/slaveworx/tg-starter-theme/wiki)

## Requirements

To run this project, you need to have the following software installed on your system:

- [Node.js](https://nodejs.org/en/download/) **`(^ 16.0.0)`**: A JavaScript runtime built on Chrome's V8 JavaScript engine. Download and install the recommended version for your platform.
- [npm](https://www.npmjs.com/get-npm) **`(^ 7.10.0)`**: The package manager for JavaScript and the world's largest software registry. It is included with Node.js, so no separate installation is needed.

Please ensure you have **`Node.js`** and **`npm`** installed before proceeding.
## Installation

To install and use **`TG Starter Theme`**, follow these steps:

1. Make sure you have Node.js and npm installed on your system. If not, follow the [official Node.js guide](https://nodejs.org/en/download/package-manager/) to install **`Node.js`** and **`npm`**.
2. Clone the repository or download the ZIP file to your local machine.
3. Add the exported folder to your wordpress theme's folder.
4. Navigate to the **`tg`** folder via your terminal.
5. Run **`npm install`** to install all dependencies. After the install is completed you will be prompted to add your dev server URL.
6. Run **`npm link`** to link local packages.
7. Once the installation is completed, you can use the **`tg`** CLI and enjoy the revolution of wordpress theme development.
8. Run **`tg start`** and see the magic happening!

## CLI Usage

The **`tg`** CLI comes with several commands to help you generate different types of templates for your Wordpress theme:

- **`tg start`**: Start watchers and the development server with live reload.
- **`tg generate:component`**: Generate a new component.
- **`tg generate:archive`**: Generate a new archive template.
- **`tg generate:page`**: Generate a new page template.
- **`tg generate:single`**: Generate a new single template.
- **`tg generate:combo`**: Generate new archive and single templates.
- **`tg optimise:fonts`**: Optimises all fonts inside the **`./static/fonts`** directory.


