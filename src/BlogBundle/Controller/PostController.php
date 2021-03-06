<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Post controller.
 *
 */
class PostController extends Controller
{
    /**
     * Lists all post entities.
     *
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();


        $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
        $posts = $em->getRepository('BlogBundle:Post')->findAll();

        return $this->render('BlogBundle:post:index.html.twig', array(
            'posts' => $posts,
            'catalogs' => $catalogs,
        ));
    }

    /**
     * Creates a new post entity.
     *
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm('BlogBundle\Form\PostType', $post);
        $form->handleRequest($request);

        if ( $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush($post);


            $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
            return $this->redirectToRoute('post_show', array('id' => $post->getId(),'catalogs' => $catalogs,));
        }
        $em = $this->getDoctrine()->getManager();

        $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
        return $this->render('BlogBundle:post:new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
            'catalogs' => $catalogs,
        ));
    }



    /**
     * Displays a form to edit an existing post entity.
     *
     */
    public function editAction(Request $request, Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('BlogBundle\Form\PostEditType', $post);
        $editForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_edit', array('id' => $post->getId(),'catalogs' => $catalogs,));
        }

        return $this->render('BlogBundle:post:edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'catalogs' => $catalogs,
        ));
    }

    /**
     * Deletes a post entity.
     *
     */
    public function deleteAction(Request $request, Post $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush($post);
        }
        $em = $this->getDoctrine()->getManager();

        $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
        return $this->redirectToRoute('post_index',array('catalogs' => $catalogs,));
    }

    /**
     * Creates a form to delete a post entity.
     *
     * @param Post $post The post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        $em = $this->getDoctrine()->getManager();

        $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $post->getId(),'catalogs' => $catalogs,)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
