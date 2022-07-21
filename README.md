# Huntr

A Product Hunt clone to showcase Aurelia 2 and WordPress integration.

## Getting started

You'll need to make sure you have a local environment running for WordPress. You'll need PHP, Apache/Nginx and a database (MySQL).

You will also need the Composer package manager installed to install dependencies. WordPress and others are installed as packages from the `composer.json` file.

- `composer install` to install dependencies
- Copy `.env.example` to `.env`
- Update the database connection details in this environment file to point to your database

One very important thing to note is that WordPress is configured to be installed in a folder (`wp`). This means that you will need to create rewrite rules for your server. Fortunately, there is a `nginx.conf` file in this project which gives you the needed rewrite rules to ensure your application looks like `https://myapp.com/` and not `https://myapp.com/wp`