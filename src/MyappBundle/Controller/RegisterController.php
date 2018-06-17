<?php

namespace MyappBundle\Controller;

use MyappBundle\Entity\User;
use MyappBundle\Form\Type\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends Controller
{

    public function indexAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class,$user, array('method' => 'POST'));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $username=$user->getEmail();
            $plainPassword=$user->getPassword();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->setUsername($username);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $message="Well done! Your account was created successfully";
            $this->get('session')->getFlashBag()->add('message',$message);
            return $this->forward('MyappBundle:Security:login');
//            return $this->redirect($this->generateUrl('myapp_login', array('message' => $message)));
        }

        return $this->render('MyappBundle:Default:admin/register.html.twig', array('form' => $form->createView(),));
    }
}
