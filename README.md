# PST-Stack
PST (Propel ORM - Slim Framework - Twig Template Engine) Stack in PHP is the most sophisticated stack to quickly build any kinds of PHP applicaiton. This open source application will let you create your application within maximum 120 seconds (2 minutes) without any hassle.

This stack was first created to quickly build any prototype or any quick but powerful web applicaiton within hour or day and after that I made it open source to make your work more easier.

It includes user authentication/registration and some useful middleware (authenticated-/guest-only routes, CSRF, validation, saving old form input across requests).

## Installation

### Install Composer
If you have not installed [Composer](https://getcomposer.org/), do that now.

Under *nix I prefer to install Composer globally in `/usr/local/bin`, but you may also install Composer locally in your current working directory.

Under Windows I prefer the [Installer](https://getcomposer.org/doc/00-intro.md#using-the-installer) for global installation.

[Composer Installation Guide](https://getcomposer.org/doc/00-intro.md#installation)

### Install the Application
After you have installed Composer, run these command from the directory in which you want to install your new Propel-Slim-Twig Application stack.

```bash
$ git clone https://github.com/koelle25/pst-stack [your-app-name]
$ composer install
```

Replace `[your-app-name]` with the desired directory name for your new application. You'll want to:
- Point your virtual host document root to your new application's `public/` directory
- Create a database (e.g. MySQL/phpMyAdmin)
- Configure Propel and generate it's ORM classes.
  - Copy/paste `config/settings.sample` => `config/settings.php`
  - Copy/paste `propel/propel.sample` => `propel/propel.xml`
  - Replace all `DatabaseUsername`, `DatabasePassword`, `DatabaseName` in `config/settings.php` and `propel/propel.xml`
  - Edit `propel/schema.xml` according to your needs
- Now genereate Propel ORM classed by issuing the following commands:

  ```bash
  #go into your project root (e.g. /var/www/your-app-name)
  $ cd /var/www/your-app-name
  $ cd propel
  $ ../vendor/propel/propel/bin/propel sql:build
  $ ../vendor/propel/propel/bin/propel model:build
  $ ../vendor/propel/propel/bin/propel config:convert
  ```

- Again, go into your project root and make `/tmp` writable

  ```bash
  $ cd /var/www/your-app-name
  #need once more composer install command to autoload newly generated propel classmap
  $ composer install
  $ chmod -R 777 tmp/
  ```

- Import the generated SQL file into your database, either by using phpMyAdmin or on the command line:

  ```bash
  $ cd /var/www/your-app-name/propel/generated-sql
  $ mysql -u [DatabaseUsername] -p [DatabaseName] < [DatabaseName].sql
  Password: [DatabasePassword]
  ```

That's it! Now go build something cool. Go to your browser and type your application host (according to your virtual host). You can signup and login into the application by yourself.

## Credits
This stack is build upon following resources:
- [PreviewTechnologies/pst-stack](https://github.com/PreviewTechnologies/pst-stack)
- [StyxOfDynamite/pst-stack](https://github.com/StyxOfDynamite/pst-stack)
- [Codecourse/Authentication with Slim 3](https://www.youtube.com/playlist?list=PLfdtiltiRHWGc_yY90XRdq6mRww042aEC)