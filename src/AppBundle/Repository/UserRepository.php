<?php
/**
 * Created by PhpStorm.
 * User: Misha Savitckiy
 * Date: 26.11.16
 * Time: 20:11
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository  implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        $em = $this->getEntityManager();
        $builder = $em->getRepository('AppBundle:User')->createQueryBuilder('user');
        $query = $builder
            ->where('user.username = :login')
            ->orWhere('user.email = :login')
            ->setParameter(':login', $username)
            ->getQuery();
        $user = $query->getOneOrNullResult();
        return $user;
    }
    public function findOneByEmail(string $email)
    {
        return $this->findOneBy(array('email' => $email));
    }

}