<?php
/**
 * Created by PhpStorm.
 * User: Misha Savitckiy
 * Date: 26.11.16
 * Time: 01:45
 */
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * RestoreToken
 *
 * @ORM\Table(name="restore_token")
 * @ORM\Entity(repositoryClass="AppBundle\Controller\TokenRepository")
 * @UniqueEntity(fields={"token"})
 */
class RestoreToken
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;
    /**
     * @ORM\Column(name="token", type="string", length=255, unique=true)
     */
    private $token;
    public function __construct($user)
    {
        $this->user = $user;
        $this->token = md5(uniqid());
    }
    public function getId()
    {
        return $this->id;
    }
    public function getUser()
    {
        return $this->user;
    }
    public function getToken()
    {
        return $this->token;
    }
}
