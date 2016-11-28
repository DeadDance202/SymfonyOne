<?php
/**
 * Created by PhpStorm.
 * User: DeadDance
 * Date: 15.11.2016
 * Time: 15:58
 */
namespace AppBundle\Controller;

use AppBundle\Entity\RestoreToken;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class SecurityController extends Controller
{
/**
 * @Route(path="/login", name="login")
 * @Route(path="/", name="homepage")
 */
public function loginAction(Request $request)
{

    $authenticationUtils = $this->get('security.authentication_utils');
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();
    return $this->render('AppBundle:security:Login.html.twig', array(

        'form'=>$form->createView(),
        'last_username' => $lastUsername,
        'error'         => $error,
    ));
}
/**
 * @Route("/logout", name="security_logout")
 */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route(path="/restore", name="restore")
     */
    public function restoreAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form
            ->remove('username')
            ->remove('plainPassword')
            ->add('Send', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')->findOneByEmail($user->getEmail());
            $token = $em->getRepository('AppBundle:RestoreToken')->findOneBy(array('user' => $user));
            if ($token != null) {
                $em->getRepository('AppBundle:RestoreToken')->removeToken($token);
            }
            do {
                $token = new RestoreToken($user);
                $validator = $this->get('validator');
                $errors = $validator->validate($token);
            } while (count($errors) > 0);
            $em->persist($token);
            $message = \Swift_Message::newInstance()
                ->setSubject('Восстановление пароля ')
                ->setFrom('recovery@Guitarslands.by')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'security/recoveryEmail.html.twig', array(
                            'username' => $user->getUsername(),
                            'token' => $token->getToken(),
                    )),
                    'text/html'
                );
            $this->get('mailer')->send($message);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('security/email.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route(path="/restore/{token}", name="restoreToken")
     */
    public function restoreTokenAction($token, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $token = $em->getRepository('AppBundle:RestoreToken')->findToken($token);
        $user = $token->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form
            ->remove('username')
            ->remove('roles')
            ->remove('email')
            ->remove('plainPassword')
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat password'),
            ))
            ->add('submit', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $encoder = $this->container->get('security.password_encoder');
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->remove($token);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('security/restore.html.twig', array(
            'form' => $form->createView(),
            'username' => $user->getUsername(),
        ));
    }
}