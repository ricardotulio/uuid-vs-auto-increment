<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\DBAL\Connection;
use App\Entity\Person;
use Ramsey\Uuid\Uuid;

final class PersonService implements PersonServiceInterface
{

    /**
     * @param Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function get($id): Person
    {
        $sql = 'SELECT LCASE(HEX(id)) AS id, name, created, updated 
            FROM person 
            WHERE LCASE(HEX(id)) = :id';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $personData = $stmt->fetch();

        $person = new Person();
        $person->fromArray($personData);

        return $person;
    }

    /**
     * {@inheritDoc}
     */
    public function persist(Person $person): Person
    {
        $uuid = Uuid::uuid4();

        $sql = 'INSERT INTO person (id, name) 
            VALUES (:id, :name)';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(
            [
                ':id' => $uuid->getBytes(),
                ':name' => $person->getName()
            ]
        );

        return $this->get($uuid->getHex());
    }
}
