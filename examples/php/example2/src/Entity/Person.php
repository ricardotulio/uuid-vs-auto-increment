<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * @codeCoverageIgnore
 */
final class Person
{
    use Fillable;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * Define Person id
     *
     * @param int $id
     * @return App\Entity\Person
     */
    public function withId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns Person id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Define Person name
     *
     * @param string $name
     * @return App\Entity\Person
     */
    public function withName($name): self
    {
        return $this->name = $name;
        return $this;
    }

    /**
     * Returns Person name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
