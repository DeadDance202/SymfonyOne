<?php
/**
 * Created by PhpStorm.
 * User: Misha Savitckiy
 * Date: 25.11.16
 * Time: 04:14
 */
namespace BlogBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Symfony\Component\Serializer\Annotation\Groups;

    /**
     * Category
     *
     * @ORM\Table(name="categories")
     * @ORM\Entity(repositoryClass="BlogBundle\Entity\CategoryRepository")
     * @UniqueEntity("name", message = "Username already exist")
     */
    class Category
    {
        /**
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         *
         * @Groups({"category"})
         */
        private $id;
        /**
         * @ORM\Column(name="category_name", type="string", length=255, unique=true)
         *
         * @Groups({"category"})
         */
        private $name;
        /**
         * @ORM\Column(name="is_active", type="boolean")
         */
        private $isActive;
        /**
         * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
         *
         * @Groups({"category"})
         */
        private $children;
        /**
         * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
         * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
         */
        private $parent;
    }
}