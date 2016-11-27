<?php
namespace BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class KnpController extends Controller
{
    /**
     * @Route(path="/category/{id}", name="category")
     *
     */
public function indexAction($id)
    {

    $repository=$this->getDoctrine()->getRepository('BlogBundle:Product');
    $products=$repository->findBy(array('category'=>$id));
        $em = $this->getDoctrine()->getManager();
        $name = $em->getRepository('BlogBundle:Category')->find($id);
    return $this->render('BlogBundle:Knp:index.html.twig',array('products'=>$products,'name'=>$name));
    }
}