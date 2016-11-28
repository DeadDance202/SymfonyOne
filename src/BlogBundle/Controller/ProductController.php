<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('BlogBundle:Product')->findAll();

        return $this->render('BlogBundle:product:index.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Creates a new product entity.
     *
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $product->setCreatedAt(new \DateTime($timezone="Europe/Vilnius"));
        $product->setUpdatedAt(new \DateTime($timezone="Europe/Vilnius"));
        $form = $this->createForm('BlogBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $product->getImage();
            $fileName = $this->get('app.imgUploader')->upload($file);
            $product->setImage('/images/product/'.$fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush($product);

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('BlogBundle:product:new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route(path="/{id}/show", name="product_show", requirements={"id": "\d+"})
     *
     *
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('BlogBundle:product:show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('BlogBundle\Form\ProductType', $product);
        $editForm->remove('createdAt');

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $product->getImage();
            $fileName = $this->get('app.imgUploader')->upload($file);
            $product->setImage('images/product/'.$fileName);
            $product->setUpdatedAt(new \DateTime($timezone="Europe/Vilnius"));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('BlogBundle:product:edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush($product);
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
