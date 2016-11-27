<?php

namespace BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('BlogBundle:Post')->findAll();
        $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
        return $this->render('BlogBundle:Default:index.html.twig', array(
            'posts' => $posts,
            'catalogs' => $catalogs,
        ));
    }
    /*    $repository=$this->getDoctrine()->getRepository('BlogBundle:Category');
        $serch=$repository->findBy(array('name'=>$id));

        return $this->render('BlogBundle:Default:index.html.twig');
    }*/
}
