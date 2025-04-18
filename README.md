# WordPress Reverse Proxy

## Introduction

TBA

## Description

TBA

## Screenshots



## Installation

1. Upload the plugin files to the `/wp-content/plugins/wp-reverse-proxy` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

## Local Development

Local Development uses the [@wordpress/env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) environment.

> `wp-env`` lets you easily set up a local WordPress environment for building and testing plugins and themes. It’s simple to install and requires no configuration.

### Prerequisites

You need certain pieces of software on your computer before setting up this project locally.

- [Composer](https://getcomposer.org/) for installing PHP dependencies
- [Node](https://nodejs.org/en/) for installing JavaScript dependencies
- [Docker](https://docs.docker.com/get-docker/) for running the local server environment

Whilst newer versions of the above packages may be available, the versions of the these the team is predominately running are:

- PHP v8.1.x or above
- Composer v2.x
- Node.js v20.x
- npm v7.x
- Docker v20.x

### Installation

To set up this repository for local development, clone it down onto your computer and run the following steps to install Composer and npm dependencies.

```bash
git clone git@github.com:ivankristianto/wp-reverse-proxy.git wp-reverse-proxy
cd wp-reverse-proxy
composer install
npm install
npm run build

npm run server start
```

Once you run `npm run server start`, Docker will pull down the docker image and set up your development environment. This may take a few minutes to complete.

**Your site should now be running at [localhost:8888](http://localhost:8888/)!**

### Development Build

To build the plugin for development, run the following command:

```bash
npm run dev
```

This will run Vite in development mode and watch for any changes to the files in the `src` directory. Hot module replacement is enabled by default. The built files will be placed in the `assets/dist` directory.

### Lint and Unit Tests

To run the lint, run the following command:

```bash
npm run lint
```

To run the unit tests, run the following command:

```bash
npm run test-unit-php
```

### Run WP-CLI

To run WP-CLI, run the following command:

```bash
npm run cli wp <commands>

#sample
npm run cli wp option get siteurl
```

## Local Development with GitHub Codespace

You can also use GitHub Codespace to develop this plugin. To do so, you can follow the steps below:

1. From GitHub repo screen
2. Click on the `Code` button
3. Click on the `Create codespace on main` button
4. Wait until the Codespace is ready

This will setup the codespace dev environment for you and setup all the dependencies.
Once the Codespace is ready, you can run the visit the url provided by GitHub Codespace terminal to access the site.

## Contributing

If you would like to contribute to this plugin, please fork the repository and submit a pull request.

## Changelog

### 0.1.0

- Initial release.
