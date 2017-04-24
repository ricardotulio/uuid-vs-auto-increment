<?php

require_once __DIR__.'/vendor/autoload.php';

$host = 'mysql';
$user = 'root';
$pass = 'root';
$dbname = 'example';

$conn = new \PDO(
    "mysql:host=${host};dbname=${dbname}",
    $user,
    $pass
);

$createTable = <<<____SQL
    CREATE TABLE IF NOT EXISTS `test_autoinc` (
        `id` BIGINT UNSINGED NOT NULL auto_increment,
        `name` VARCHAR(60) NOT NULL,
        `created` DATETIME NOT NULL DEFAULT NOW(),
        PRIMARY KEY(`id`)
    ) ENGINE=InnoDb;
____SQL;

$conn->exec($createTable);

$faker = Faker\Factory::create();

$start = microtime(true);

for ($i=0; $i < $argv[1]; $i++) {
    $insert = "INSERT INTO test_autoinc (name) VALUES (:name)";
    $stmt = $conn->prepare($insert);
    $stmt->execute([':name' => $faker->name]);
}

$end = microtime(true);
$execution = $end - $start;

$conn->exec('DROP test_autoinc');

echo $execution;
