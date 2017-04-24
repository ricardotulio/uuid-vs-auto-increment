<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Person;

interface PersonServiceInterface
{
    /**
     * Retrivies a Person
     *
     * @param int $id;
     * @return App\Entity\Person
     */
    public function get($id): Person;

    /**
     * Persist a Person
     *
     * @param App\Entity\Person $person
     * @return App\Entity\Person
     */
    public function persist(Person $person): Person;
}
