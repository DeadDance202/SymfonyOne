<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**

     * @Route(path="/", name="admin_home")
     */
    public function homeAction()
    {
        return $this->forward('AppBundle:Admin:usersShow');
    }

    private function getAjaxData($repository, $parameters, $perPage, $arrayPushFunc)
    {
        $query = $repository->createQuery($parameters);
        $paginator = $repository->paginate($query, @$parameters['page'] ?: 1, $perPage);
        $data = $paginator->getCurrentPageResults();
        $items = array();
        foreach ($data as $item) {
            $arrayPushFunc($items, $item);
        }
        return array('rows' => $paginator->getNbResults(), 'data' => $items);
    }

    /**
     * @Route(path="/posts", name="admin_posts_show")
     */
    public function postsShowAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $repository = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Post');
            $parameters = $request->query->all();
            $response = $this->getAjaxData(
                $repository,
                $parameters,
                Post::POSTS_PER_PAGE,
                function (&$items, $item){
                    array_push($items, array(
                        'title' => $item->getTitle(),
                        'category' => $item->getCategory()->getName(),
                        'creationDate' => $item->getCreationDate()->format('Y-m-d H:i:s'),
                    ));
                }
            );
            return $this->json($response);
        }
        return $this->render('admin/ajax_posts_show.html.twig');
    }
    /**
     * @Route(path="/users", name="admin_users_show")
     */
    public function usersShowAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $parameters = $request->query->all();
            $repository  = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
            $response = $this->getAjaxData(
                $repository,
                $parameters,
                User::USERS_PER_PAGE,
                function (&$items, $item){
                    array_push($items, array(
                        'username' => $item->getUsername(),
                        'email' => $item->getEmail(),
                        'roles' => $item->getRoles()[0],
                    ));
                }
            );
            return $this->json($response);
        }
        return $this->render('admin/ajax_users_show.html.twig');
    }

    /**
     * @Route(path="/user/{id}", name="admin_user_edit", requirements={"id": "\d+"})
     */
    public function userEditAction(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user)
            ->remove('email')
            ->remove('username')
            ->remove('PlainPassword')
            ->add('submit', SubmitType::class);
        $form->get('roles')->setData($user->getRoles()[0]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setRoles($form->get('roles')->getData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('admin_users_show');
        }
        return $this->render('admin/user_edit.html.twig', array(
            'form' => $form->createView(),
            'username' => $user->getUsername(),
        ));
    }
    /**
     * @Route(path="/category", name="admin_categories_show")

     */
    public function categoriesShowAction(Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            $parameters = $request->query->all();
            $repository = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Category');
            $response = $this->getAjaxData(
                $repository,
                $parameters,
                Category::CATEGORIES_PER_PAGE,
                function (&$items, $item){
                    array_push($items, array('name' => $item->getName()));
                }
            );
            return $this->json($response);
        }
        return $this->render('admin/ajax_category_show.html.twig');

    }
}
