<?php
namespace BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class KnpController extends Controller
{
    /**
     * @Route(path="/{id}", name="category", requirements={"id": "\d+"})
     * @Route("/blog/{page}", name="blog_list", requirements={"page": "\d+"})
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