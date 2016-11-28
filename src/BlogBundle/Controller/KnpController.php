<?php
namespace BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class KnpController extends Controller
{
    /**
     * @Route(path="/{id}", name="category", requirements={"id": "\d+"})
     * @Route("/blog/{page}", name="blog_list", requirements={"page": "\d+"})
     */
    public $articlesPerPage = 8;
    public function indexAction(Request $request,$id)
    {

        $repository=$this->getDoctrine()->getRepository('BlogBundle:Product');
        $products=$repository->findBy(array('category'=>$id));
        $em = $this->getDoctrine()->getManager();
        $name = $em->getRepository('BlogBundle:Category')->find($id);

        $articles = $repository->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            $this->articlesPerPage);
        return $this->render('BlogBundle:Knp:index.html.twig',array('products'=>$products,'name'=>$name,'pagination' => $pagination));

    }
}