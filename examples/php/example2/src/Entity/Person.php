<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * @codeCoverageIgnore
 */
final class Person
{
    use Mapper;

    /**
     * @var array
     */
    private $fillable = [ 'id', 'name', 'created', 'updated' ];

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $created;

    /**
     * @var string
     */
    private $updated;

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
    public function withName(string $name): self
    {
        $this->name = $name;
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

    /**
     * Define a created
     *
     * @param string $created
     */
    public function withCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Retrivies created data
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param string $updated
     */
    public function withUpdated($updated): self
    {
        $this->updated = $updated;
        return $this;
    }
}
