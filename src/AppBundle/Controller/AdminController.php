<?php
/**
 * Created by PhpStorm.
 * User: DeadDance
 * Date: 15.11.2016
 * Time: 14:58
 */
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @Route(path="/admin", name="admin")
     */
    public function adminAction()
    {
        return $this->render('AppBundle:security:Login.html.twig');
    }
}