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
        `id` BINARY(16) NOT NULL,
        `name` VARCHAR(60) NOT NULL,
        `created` DATETIME NOT NULL DEFAULT NOW()
    ) ENGINE=InnoDb;

    DELIMITER //
    CREATE FUNCTION `binary_uuid`()
    RETURNS binary(16) DETERMINISTIC
    RETURN UNHEX(REPLACE(UUID(), '-', ''))
    //
    DELIMITER ;
____SQL;

$conn->exec($createTable);

$faker = Faker\Factory::create();

$start = microtime(true);

for ($i=0; $i < $argv[1]; $i++) {
    $insert = "INSERT INTO test_uuid (id, name) VALUES (binary_uuid(), :name)";
    $stmt = $conn->prepare($insert);
    $stmt->execute([':name' => $faker->name]);
}

$end = microtime(true);
$execution = $end - $start;

//$conn->exec('DROP FUNCTION binary_uuid');
//$conn->exec('DROP TABLE test_uuid');

echo $execution;
