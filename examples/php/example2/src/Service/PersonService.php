<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\DBAL\Connection;
use App\Entity\Person;

final class PersonService implements PersonServiceInterface
{
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function get($id): Person
    {
        try {
            $sql = "SELECT * FROM person WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        $person = new Person();

        return $person;
    }

    /**
     * {@inheritDoc}
     */
    public function persist(Person $person): Person
    {
    }
}
