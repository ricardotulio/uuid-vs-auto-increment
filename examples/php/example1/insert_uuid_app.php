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
    CREATE TABLE IF NOT EXISTS `test_uuid` (
        `id` CHAR(36) NOT NULL,
        `name` VARCHAR(60) NOT NULL,
        `created` DATETIME NOT NULL DEFAULT NOW()
    ) ENGINE=InnoDb;
____SQL;

$conn->exec($createTable);

$faker = Faker\Factory::create();
$uuid4 = Ramsey\Uuid\Uuid::uuid4();

$start = microtime(true);

for ($i=0; $i < $argv[1]; $i++) {
    $insert = "INSERT INTO test_uuid (id, name) VALUES (:id, :name)";
    $stmt = $conn->prepare($insert);
    $stmt->execute(
        [
            ':id' => $uuid4->getBytes(),
            ':name' => $faker->name
        ]
    );
}

$end = microtime(true);
$execution = $end - $start;

$dropTable = 'DROP test_uuid';
$conn->exec($dropTable);

echo $execution;
