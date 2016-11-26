<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('BlogBundle:Category')->findAll();


        $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
        return $this->render('BlogBundle:category:index.html.twig', array(
            'categories' => $categories,
            'catalogs' => $catalogs,
        ));
    }

    /**
     * Creates a new category entity.
     *
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('BlogBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush($category);


            $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
            return $this->redirectToRoute('category_show', array('id' => $category->getId(),'catalogs' => $catalogs,));
        }
        $em = $this->getDoctrine()->getManager();

        $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
        return $this->render('BlogBundle:category:new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
            'catalogs' => $catalogs,
        ));
    }

    /**
     * Finds and displays a category entity.
     *
     */
    public function showAction(Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $em = $this->getDoctrine()->getManager();

        $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
        return $this->render('BlogBundle:category:show.html.twig', array(
            'category' => $category,
            'delete_form' => $deleteForm->createView(),
            'catalogs' => $catalogs,
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     */
    public function editAction(Request $request, Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('BlogBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_edit', array('id' => $category->getId(),'catalogs' => $catalogs));
        }

        return $this->render('BlogBundle:category:edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'catalogs' => $catalogs,
        ));
    }

    /**
     * Deletes a category entity.
     *
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush($category);
        }

        return $this->redirectToRoute('category_index',array('catalogs' => $catalogs,));
    }

    /**
     * Creates a form to delete a category entity.
     *
     * @param Category $category The category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
