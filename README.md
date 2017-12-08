# PST-Stack
PST (Propel ORM - Slim Framework - Twig Template Engine) Stack in PHP is the most sophisticated stack to quickly build any kind of PHP application. This open source technology stack will let you create your application within a maximum of 120 seconds (2 minutes) without any hassle.

This stack was first created to quickly build any prototype or any quick but powerful web application within hours or a day and after that I made it open source to make your work more easy.

It includes user authentication/registration and some useful middleware (authenticated-/guest-only routes, CSRF, validation, saving old form input across requests).

## Installation

### Install Composer
If you haven't installed [Composer](https://getcomposer.org/) yet, do that now.

Under *nix I prefer to install Composer globally in `/usr/local/bin`, but you may also install Composer locally in your current working directory.

Under Windows I prefer the [Installer](https://getcomposer.org/doc/00-intro.md#using-the-installer) for global installation.

[Composer Installation Guide](https://getcomposer.org/doc/00-intro.md#installation)

### Install the Application
After you have installed Composer, run these commands from the directory in which you want to install your new Propel-Slim-Twig Application stack.

1. Clone this repository _(replace `[your-app-name]` with the desired directory name for your new application)_:  
    ```bash
    $ git clone https://github.com/koelle25/pst-stack [your-app-name]
    $ cd [your-app-name]
    $ composer install
    ```

2. Point your virtual host document root to your new application's `public/` directory  
3. Create a database, e.g. by using [phpMyAdmin](https://docs.phpmyadmin.net/en/latest/) or on the command line. When using mysql you can do _(replace `[your-database-name]` with the desired database name for your new application)_:  
    ```bash
    $ mysql -u root -p create [your-database-name]
    ```

4. Configure Propel and generate it's ORM classes:  
    - Copy/paste `config/settings.sample` => `config/settings.php`
    - Copy/paste `propel/propel.sample` => `propel/propel.xml`
    - Replace `DatabaseUsername`, `DatabasePassword`, `DatabaseName` in `propel/propel.xml`
    - Edit `config/settings.php` according to your needs
    - Edit `propel/schema.xml` according to your needs
5. Now generate Propel ORM classed by issuing the following commands:
    - Go into the propel directory in your project root (e.g. /var/www/[your-app-name]):
        ```bash
        $ cd /var/www/[your-app-name]/propel
        ```
    - Build sql, model and config and insert the schema into your database:
        ```bash
        $ ../vendor/propel/propel/bin/propel sql:build
        $ ../vendor/propel/propel/bin/propel model:build
        $ ../vendor/propel/propel/bin/propel config:convert
        $ ../vendor/propel/propel/bin/propel sql:insert
        ```  
6. _*nix only_: Make `tmp` writable:
    ```bash
    $ cd /var/www/[your-app-name]
    $ chmod -R 777 tmp/
    ```

That's it! Now go build something cool. Go to your browser and type your application host (according to your virtual host). You can signup and login into the application by yourself.

## Credits
This stack is built upon following resources:
- [PreviewTechnologies/pst-stack](https://github.com/PreviewTechnologies/pst-stack)
- [StyxOfDynamite/pst-stack](https://github.com/StyxOfDynamite/pst-stack)
- [Codecourse/Authentication with Slim 3](https://www.youtube.com/playlist?list=PLfdtiltiRHWGc_yY90XRdq6mRww042aEC)
- [Propel ORM](http://propelorm.org/)
- [Slim Framework](https://www.slimframework.com/)
- [Twig Template Engine](https://twig.symfony.com/)
- [Bootstrap 4.0](http://getbootstrap.com/docs/4.0/)
- [FontAwesome 5](https://fontawesome.com/)