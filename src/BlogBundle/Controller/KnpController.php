<?php
namespace BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class KnpController extends Controller
{
    /**
     * @Route(path="/{id}", name="category")
     *
     */
public function indexAction($id)
    {

    $repository=$this->getDoctrine()->getRepository('BlogBundle:Category');
    $category=$repository->findBy(array('id'=>$id));

    return $this->render('BlogBundle:Knp:index.html.twig',array('category'=>$category));
    }
}