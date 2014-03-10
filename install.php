<?php
require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();

$app->get('/', function() use ($app) {

    $response = '';

    exec('php src/console.php orm:schema-tool:update --force', $output, $return);

    foreach ($output as $string) {
        $response .= '<pre>' . $string . '</pre>';
    }

    $response .= '<p><strong>Installation successful</strong></p>';

    $response .= '<p>You can create new super admin user by executing the shell command <code>php src/console.php user:create [username] [password] [email] [--roles[="ROLE_SUPER_ADMIN"]]</code></p>';

    return $response;
});

$app->run();