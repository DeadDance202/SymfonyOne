<?php
/**
 * Created by PhpStorm.
 * User: Misha Savitckiy
 * Date: 26.11.16
 * Time: 01:58
 */

namespace AppBundle\Controller;
class TokenRepository extends \Doctrine\ORM\EntityRepository
{
    public function findToken(string $token)
    {
        return $this->findOneBy(array('token' => $token));
    }
    public function removeToken($token)
    {
        $this->getEntityManager()->remove($token);
        $this->getEntityManager()->flush();
    }
}