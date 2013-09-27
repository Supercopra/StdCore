<?php
namespace Tett\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Tett\Repository\user")
 */
class user
{
	/**
	 * @ORM\Column(type="String")
	 */
	protected $name;

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="Integer")
	 */
	protected $id;

  

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
