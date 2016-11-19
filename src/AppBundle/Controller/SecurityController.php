<?php
/**
 * Created by PhpStorm.
 * User: DeadDance
 * Date: 15.11.2016
 * Time: 15:58
 */
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
/**
 * @Route(path="/login", name="login")
 */
public function loginAction(Request $request)
{

    $authenticationUtils = $this->get('security.authentication_utils');

    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('AppBundle:security:Login.html.twig', array(
        'last_username' => $lastUsername,
        'error'         => $error,
    ));
}
}