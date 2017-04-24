<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__.'/../vendor/autoload.php';

use App\Http\Controller\V1\Person\Get as GetPerson;
use App\Http\Controller\V1\Person\Create as CreatePerson;
use App\Service\PersonService;

$app = new \Slim\App();

$config = array(
    'dbname' => 'example',
    'user' => 'root',
    'password' => 'root',
    'host' => 'mysql',
    'driver' => 'pdo_mysql',
);

$conn = \Doctrine\DBAL\DriverManager::getConnection($config);

$app->get('/v1/person/{id}', new GetPerson(new PersonService($conn)));
$app->post('/v1/person', new CreatePerson());

$app->run();
