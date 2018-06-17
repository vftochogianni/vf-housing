<?php

namespace MyappBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

class SecurityController extends Controller
{
    /**
     * @Template()
     */
    public function loginAction(Request $request)
    {

//        $userInput = new User();
//        $error = "";
//
//        $form = $this->createForm(LoginType::class, $userInput, array('method' => 'POST'));
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted()) {
//
//            $emailInput = $userInput->getEmail();
//            $passwordInput = $userInput->getPassword();
//
//            $repository = $this->getDoctrine()->getManager()->getRepository('MyappBundle:User');
//            $user = $repository
//                ->findOneBy(array('email' => $emailInput, 'password' => $passwordInput));
//
//            if (!$user) {
//                $error = "Wrong Email address or password";
//            } else {
//                $_SESSION['id']=$user->getId();
//                return $this->redirect($this->generateUrl('myapp_homepage',array('name' => $user->getName())));
//            }
//        }

//        $authenticationUtils = $this->get('security.authentication_utils');
//
//        // get the login error if there is one
//        $error = $authenticationUtils->getLastAuthenticationError();
//
//        // last username entered by the user
//        $lastUsername = $authenticationUtils->getLastUsername();
//
//        return $this->render('MyappBundle:Default:login.html.twig', array(
//                'last_username' => $lastUsername,
//                'error'         => $error,
//            ));
        $session = $request->getSession();

        // get the login error if there is one
        $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);


        return $this->render(
            'MyappBundle:Default:admin/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContextInterface::LAST_USERNAME),
                'error'         => $error
            )
        );
    }


    public function logoutAction()
    {
    }
}