# CodeIgniter Raft

**CodeIgniter Raft** is a lightweight, Docker-powered development environment for CodeIgniter 4 that simplifies local development workflows. Built with PHP-FPM and Apache2, it provides a ready-to-use containerized setup with zero configuration required.

![CodeIgniter Raft](https://via.placeholder.com/800x200?text=CodeIgniter+Raft)

## Introduction

CodeIgniter Raft provides a Docker-powered local development experience for CodeIgniter 4 that is compatible with macOS, Windows (WSL2), and Linux. Other than Docker, no software or libraries are required to be installed on your local computer before using Raft. Raft's simple CLI means you can start building your CodeIgniter application without any previous Docker experience.

## Installation

You can install CodeIgniter Raft as a development dependency in your CodeIgniter project:

```bash
composer require pipo/raft
```

After installing CodeIgniter Raft, run the following command to set up the Raft configuration files in your project:

```bash
./raft install
```

This command will copy the necessary Docker configuration files to your project's root directory.

## Basic Usage

### Starting & Stopping Raft

To start all of the Docker containers defined in your application's `docker-compose.yml` file:

```bash
./raft up
```

To stop all of the Docker containers:

```bash
./raft down
```

### Executing Commands

You can execute commands inside the application container using the `raft` script:

#### PHP Commands

```bash
./raft php -v
```

#### Composer Commands

```bash
./raft composer require codeigniter4/devkit
```

#### CodeIgniter CLI Commands

```bash
./raft spark migrate
./raft spark db:seed UserSeeder
```

#### Running Tests

```bash
./raft test
```

#### Accessing MySQL

```bash
./raft mysql
```

#### Opening a Shell

```bash
./raft shell
```

### Configuring A Shell Alias

By default, Raft commands are invoked using the `raft` script that is included with your project:

```bash
./raft up
```

However, instead of repeatedly typing `./raft` to execute Raft commands, you may wish to configure a shell alias that allows you to execute Raft's commands more easily:

```bash
alias raft='sh $([ -f raft ] && echo raft || echo vendor/bin/raft)'
```

To make sure this is always available, you may add this to your shell configuration file in your home directory, such as `~/.zshrc` or `~/.bashrc`, and then restart your shell.

Once the shell alias has been configured, you may execute Raft commands by simply typing `raft`. The remainder of this documentation's examples will assume that you have configured this alias:

```bash
raft up
```

## Available Services

By default, Raft includes the following services:

- **raft-app**: The CodeIgniter application with PHP 8.2 and Apache
- **mysql**: MySQL 8.0 database
- **redis**: Redis server
- **mailhog**: MailHog for testing emails

## Customization

### Environment Variables

The following environment variables can be added to your project's `.env` file to customize your Raft installation:

```
APP_PORT=80
DB_PORT=3306
REDIS_PORT=6379
MAILHOG_PORT=1025
MAILHOG_DASHBOARD_PORT=8025
```

### Adding Custom Services

You can add additional services to your application by creating a `docker-compose.override.yml` file in your project's root directory.

## Requirements

- Docker
- Docker Compose
- PHP 7.4 or higher
- Composer

## License

CodeIgniter Raft is open-sourced software licensed under the [MIT license](LICENSE.md).