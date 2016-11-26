<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Catalog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Catalog controller.
 *
 */
class CatalogController extends Controller
{
    /**
     * Lists all catalog entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $catalogs = $em->getRepository('BlogBundle:Catalog')->findAll();

        return $this->render('BlogBundle:catalog:index.html.twig', array(
            'catalogs' => $catalogs,
        ));
    }

    /**
     * Creates a new catalog entity.
     *
     */
    public function newAction(Request $request)
    {
        $catalog = new Catalog();
        $form = $this->createForm('BlogBundle\Form\CatalogType', $catalog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $catalog->getImage();
            $fileName = $this->get('app.ImgUploader')->upload($file);
            $catalog->setImage('images/catalog/'.$fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($catalog);
            $em->flush($catalog);

            return $this->redirectToRoute('catalog_show', array('id' => $catalog->getId()));
        }

        return $this->render('BlogBundle:catalog:new.html.twig', array(
            'catalog' => $catalog,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a catalog entity.
     *
     */
    public function showAction(Catalog $catalog)
    {
        $deleteForm = $this->createDeleteForm($catalog);

        return $this->render('BlogBundle:catalog:show.html.twig', array(
            'catalog' => $catalog,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing catalog entity.
     *
     */
    public function editAction(Request $request, Catalog $catalog)
    {
        $deleteForm = $this->createDeleteForm($catalog);
        $editForm = $this->createForm('BlogBundle\Form\CatalogType', $catalog);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('catalog_edit', array('id' => $catalog->getId()));
        }

        return $this->render('BlogBundle:catalog:edit.html.twig', array(
            'catalog' => $catalog,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a catalog entity.
     *
     */
    public function deleteAction(Request $request, Catalog $catalog)
    {
        $form = $this->createDeleteForm($catalog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($catalog);
            $em->flush($catalog);
        }

        return $this->redirectToRoute('catalog_index');
    }

    /**
     * Creates a form to delete a catalog entity.
     *
     * @param Catalog $catalog The catalog entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Catalog $catalog)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('catalog_delete', array('id' => $catalog->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
