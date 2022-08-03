# Huntr

A Product Hunt clone to showcase Aurelia 2 and WordPress integration. Want to see the app running? I bought a domain name for this (seriously): https://itemhuntr.com

By using Aurelia 2 as the front-end application, we interact with WordPress using its JSON REST API, commonly referred to as headless WordPress.

**The feature set includes:**

- Authentication using JSON Web Tokens (JWT). Allow users to register and login to your application.
- Submitting new products
- Voting functionality for logged in users to vote up products
- User profile page
- Collections
- Search
- Media uploads

The Aurelia 2 application utilises the following functionality and concepts:

- Routing using the `@aurelia/router` package
- Route guards showcasing how you can create the concept of public and protected routes
- Web Components and Shadow DOM styling as well as CSS Variables.
- Validation using the `@aurelia/validation` package to validate user input
- The  `@aurelia/fetch-client` to make API requests, working with JWT bearer tokens (sending the token up in an Authorization header) and other concepts crucial in modern web applications
- Unit testing using Jest. Learn how to test Aurelia 2 components/attributes and other parts of your application.

The purpose of this demo is to showcase how you can build feature-rich applications using Aurelia 2. Best practices around the use of singleton services, working with API's, uploading files, routing, working with Shadow DOM and other concepts you might be interested in.

## Getting started

You'll need to make sure you have a local environment running for WordPress. You'll need PHP, Apache/Nginx and a database (MySQL).

You will also need the Composer package manager installed to install dependencies. WordPress and others are installed as packages from the `composer.json` file.

- `composer install` to install dependencies
- Copy `.env.example` to `.env`
- Update the database connection details in this environment file to point to your database
- Setup WordPress and select the theme, activate plugins

One very important thing to note is that WordPress is configured to be installed in a folder (`wp`). This means that you will need to create rewrite rules for your server. Fortunately, there is a `nginx.conf` file in this project which gives you the needed rewrite rules to ensure your application looks like `https://myapp.com/` and not `https://myapp.com/wp`

**Requires ACF Pro**:

This demo currently makes use of fields only available in the Advanced Custom Fields Pro plugin, which is paid. The free version of the plugin does not have a repeater field which is used for some aspects like voting on products. You can choose not to use this functionality, however, you will lose features such as voting.
