<?php

namespace Tett\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * user
 */
class user
{
    /**
     * @var String
     */
    private $name;

    /**
     * @var Integer
     */
    private $id;


    /**
     * Set name
     *
     * @param \String $name
     * @return user
     */
    public function setName(\String $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return \String 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return \Integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
