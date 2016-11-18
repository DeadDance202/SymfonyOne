<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/add", name="homepage")
     */
    public function createAction()
    {



        $user= new User();
        $user->setUsername('admin');
        $user->setEmail('fff@ff.com');
        $user->setPassword('Dead');
        $user->setIsActive(1);

        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new user with id '.$user->getId());
    }
}
