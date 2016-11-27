<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Catalog
 *
 * @ORM\Table(name="catalog")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\CatalogRepository")
 */
class Catalog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;






    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Catalog
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set categiry
     *
     * @param \BlogBundle\Entity\Category $categiry
     *
     * @return Catalog
     */
    public function setCategiry(\BlogBundle\Entity\Category $categiry = null)
    {
        $this->categiry = $categiry;

        return $this;
    }

    /**
     * Get categiry
     *
     * @return \BlogBundle\Entity\Category
     */
    public function getCategiry()
    {
        return $this->categiry;
    }
}
