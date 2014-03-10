keratine-skeleton
=================

A skeleton for Keratine CMS project

## Installation

1. Clone this repository in your new working directory.

1. Download the [`composer.phar`](https://getcomposer.org/composer.phar) executable or use the installer.

    ``` sh
    curl -s https://getcomposer.org/installer | php
    ```

2. Run Composer: `php composer.phar update`

3. Configure the `config/parameters.yml` file with your application informations like your database's connection informations and other options you want to customize.

4. Execute the `install.php` script to create the essentials database tables for the application.

5. Execute the command `php src/console.php user:create [username] [password] [email] --roles="ROLE_SUPER_ADMIN"` to create a new super admin user to connect to the application.

The application is now ready to work!


## How to use it

- Create your Doctrine Entity classes in `src/Entity` directory and related repositories in `src/Entity/Repository`.

- Create controllers in `src/Controller/Admin` directory or create your own namespace in `src/Controller`. Controller classes must extend `Keratine\Controller\Controller` or `Keratine\Controller\CrudController`.

- Create form types in `src/Form` directory.

- Register new controllers and related base route in `src/controllers.php` by using the `Application::mount()` method like this:

    ```php
    $app->mount('/admin/hello', new Controller\Admin\HelloController());
    ```