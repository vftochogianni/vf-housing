<?php

namespace MyappBundle\Controller;

use MyappBundle\Entity\User;
use MyappBundle\Form\Type\QuestionType;
use MyappBundle\Form\Type\UpdatePasswordType;
use MyappBundle\Form\Type\UpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MyappBundle\Form\Type\ForgotPasswordType;

class UpdateController extends Controller
{
    public function indexAction(){

        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user=='anon.'){
            $error="Please login to access this page.";
            $this->get('session')->getFlashBag()->add('error',$error);
            return $this->redirect($this->generateUrl('myapp_login'));
        }else {
            return $this->render('MyappBundle:Default:admin/my_account.html.twig');
        }
    }

    public function updateAccountAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user=='anon.'){
            $error="Please login to access this page.";
            $this->get('session')->getFlashBag()->add('error',$error);
            return $this->redirect($this->generateUrl('myapp_login'));
        }else{
            $form = $this->createForm(UpdateType::class,$user, array('method' => 'POST'));
            $form->handleRequest($request);
            $id=$user->getId();

            if ($form->isSubmitted() && $form->isValid()){
                $name=$user->getName();
                $tel=$user->getTel();

                $em=$this->getDoctrine()->getManager();
                $updatedUser=$em->getRepository('MyappBundle:User')->find($id);
                $updatedUser->setName($name);
                $updatedUser->setTel($tel);
                $em->flush();
                $message="Well done! Your account details were updated successfully!";
                $this->get('session')->getFlashBag()->add('message',$message);
                return $this->redirect($this->generateUrl('myapp_my_account'));
            }
            return $this->render('MyappBundle:Default:admin/account_update.html.twig', array('form' => $form->createView(),));

        }
    }

    public function updatePasswordAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user=='anon.'){
            $error="Please login to access this page.";
            $this->get('session')->getFlashBag()->add('error',$error);

            return $this->redirect($this->generateUrl('myapp_login'));
        }else{
            $form = $this->createForm(UpdatePasswordType::class,$user, array('method' => 'POST'));
            $id=$user->getId();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $em=$this->getDoctrine()->getManager();
                $updatedUser=$em->getRepository('MyappBundle:User')->find($id);

                $plainPassword=$user->getPassword();

                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $plainPassword);
//                exit(\Doctrine\Common\Util\Debug::dump($encoded));
                $updatedUser->setPassword($encoded);
                $em->flush();
                $message="Well done! Your password was changed successfully!";
                $this->get('session')->getFlashBag()->add('message',$message);
                return $this->redirect($this->generateUrl('myapp_my_account'));

            }
            return $this->render('MyappBundle:Default:admin/password_update.html.twig', array('form' => $form->createView(),));

        }
    }

    public function forgotPasswordEmailAction(Request $request){
        $error='';
        $user= new User();
        $form=$this->createForm(ForgotPasswordType::class, $user, array('method' => 'POST'));
        $form->handleRequest($request);
        if ($form->isSubmitted() ){

            $email=$user->getUsername();
            $em=$this->getDoctrine()->getManager();
            $updatedUser=$em->getRepository('MyappBundle:User')->findOneBy(array('email'=>$email));

            if($updatedUser==null){
                $error='Wrong Email';
                $this->get('session')->getFlashBag()->add('error',$error);
            }
            else{
                return $this->redirect($this->generateUrl('myapp_answer_password', array('email'=>$email)));
            }
        }
        return $this->render('@Myapp/Default/admin/forgot_password.html.twig', array('form1' => $form->createView()));

    }

    public function questionAction(Request $request,$email){
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository('MyappBundle:User')->findOneBy(array('email'=>$email));
        $question=$user->getQuestion();
        $answer=$user->getAnswer();
        $user2=new User();
        $error='';

        $form = $this->createForm(QuestionType::class,$user2, array('method' => 'POST'));
        $form->handleRequest($request);

        if ($form->isSubmitted()){

            $check=$user2->getAnswer();
            if($answer==$check){
                return $this->redirectToRoute('myapp_reset_password',array('email'=>$email,'answer'=>$answer));
            }else{
                $error="Wrong answer!";
                $this->get('session')->getFlashBag()->add('error',$error);
            }

        }

        return $this->render('@Myapp/Default/admin/forgot_password.html.twig', array('form3' => $form->createView(), 'question'=>$question));
    }

    public function forgotPasswordPasswordAction(Request $request, $email, $answer){
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository('MyappBundle:User')->findOneBy(array('email'=>$email));
        if ($user==null or $answer!=$user->getAnswer()){
            $error="Something went wrong! Please try again.";
            $this->get('session')->getFlashBag()->add('error',$error);
            return $this->redirectToRoute('myapp_forgot_password');
        }

        $form = $this->createForm(UpdatePasswordType::class,$user, array('method' => 'POST'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $plainPassword=$user->getPassword();

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $plainPassword);
//                exit(\Doctrine\Common\Util\Debug::dump($encoded));
            $user->setPassword($encoded);
            $em->flush();

            $message="Well done! Your password was changed successfully!";
            $this->get('session')->getFlashBag()->add('message',$message);
            return $this->redirect($this->generateUrl('myapp_login'));
        }

        return $this->render('@Myapp/Default/admin/forgot_password.html.twig', array('form2' => $form->createView()));
    }

    public function deleteAccountAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user=='anon.'){
            $error="Please login to access this page.";
            $this->get('session')->getFlashBag()->add('error',$error);
            return $this->redirect($this->generateUrl('myapp_login'));
        }else {
            $id = $user->getId();
            $em2=$this->getDoctrine()->getManager();
            $housesToDelete= $em2->getRepository('MyappBundle:House')->findBy(array('user'=>$user));
            foreach ($housesToDelete as $houseToDelete) {
                $em2->remove($houseToDelete);
                $em2->flush();
            }

            $em = $this->getDoctrine()->getmanager();
            $userToDelete = $em->getRepository('MyappBundle:User')->find($id);
            $em->remove($userToDelete);
            $em->flush();

            $this->get('security.token_storage')->setToken(null);
            $this->get('request')->getSession()->invalidate();

            $message = 'Your account was deleted successfully! We are sorry to let you go :(.';
            $this->get('session')->getFlashBag()->add('message', $message);

            return $this->redirect($this->generateUrl('myapp_homepage'));
        }
    }
}
