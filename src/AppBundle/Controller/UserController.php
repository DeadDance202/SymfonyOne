<?php
/**
 * Created by PhpStorm.
 * User: DeadDance
 * Date: 28.11.2016
 * Time: 4:35
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route(path="/userpage", name="userpage")
     *
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $username=$user->getUsername();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(array('username'=> $username) );

        return $this->render('user/user.html.twig', array(
            'user' => $user,

        ));
    }
}