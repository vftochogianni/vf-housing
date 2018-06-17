<?php

namespace MyappBundle\Controller;

use MyappBundle\Entity\House;
use MyappBundle\Entity\User;
use MyappBundle\Form\Type\FilterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyappBundle\Form\Type\AddListingType;
use Symfony\Component\HttpFoundation\Request;

class ListingController extends Controller
{
    public function indexAction()
    {

    }

    public function saleAction(Request $request){
        $em=$this->getDoctrine()->getManager()->getRepository('MyappBundle:House');
        $houses=$em->findBy(array('state'=>'sale'));
        $filter=new House();
        $form=$this->createForm(FilterType::class,$filter,array('method'=>'POST'));
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $bedrooms=array();
            $floor=array();
            for($i=$filter->getBedrooms();$i<11;$i++){
                $bedrooms[]=$i;
            }
            for($i=$filter->getFloor();$i<11;$i++){
                $floor[]=$i;
            }
            $houses=$em->findBy(array('state'=>'sale', 'bedrooms' => $bedrooms, 'floor' => $floor, 'currency'=>$filter->getCurrency(), 'country'=>$filter->getCountry() ));
        }
        return $this->render('@Myapp/Default/listing/allListings.html.twig', array('houses' => $houses, 'state'=>'sale','form'=>$form->createView()));
    }

    public function rentAction(Request $request){
        $em=$this->getDoctrine()->getManager()->getRepository('MyappBundle:House');
        $houses=$em->findBy(array('state'=>'rent'));
        $filter=new House();
        $form=$this->createForm(FilterType::class,$filter,array('method'=>'POST'));
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $bedrooms=array();
            $floor=array();
            for($i=$filter->getBedrooms();$i<11;$i++){
                $bedrooms[]=$i;
            }
            for($i=$filter->getFloor();$i<11;$i++){
                $floor[]=$i;
            }
            $houses=$em->findBy(array('state'=>'rent', 'bedrooms' => $bedrooms, 'floor' => $floor, 'currency'=>$filter->getCurrency(), 'country'=>$filter->getCountry() ));
        }
        return $this->render('@Myapp/Default/listing/allListings.html.twig', array('houses' => $houses, 'state'=>'rent','form'=>$form->createView()));
    }

    public function addListingAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user=='anon.'){
            $error="Please login to access this page.";
            $this->get('session')->getFlashBag()->add('error',$error);

            return $this->redirect($this->generateUrl('myapp_login'));
        }else{
            $house = new House();
            $form=$this->createForm(AddListingType::class,$house,array('method' => 'POST'));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $em=$this->getDoctrine()->getManager();
                $house->setUser($user);
                $em->persist($house);
                $em->flush();
                $id=$house->getId();

                return $this->redirect($this->generateUrl('myapp_listing',array('house_id' => $id)));
            }
            return $this->render('@Myapp/Default/listing/addListing.html.twig', array('form'=>$form->createView()));


        }

    }

    public function updateListingAction(Request $request, $house_id){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user=='anon.'){
            $error="Please login to access this page.";
            $this->get('session')->getFlashBag()->add('error',$error);

            return $this->redirect($this->generateUrl('myapp_login'));
        }else{
            $em=$this->getDoctrine()->getManager()->getRepository('MyappBundle:House');
            $house=$em->find($house_id);
            if($house==null) {
                $error = "This listing doesn't exist anymore.";
                $this->get('session')->getFlashBag()->add('error', $error);

                return $this->redirect($this->generateUrl('myapp_homepage'));
            }
            $editor=$house->getUser();
            if($user!=$editor){
                $error="You are not the editor of this listing.";
                $this->get('session')->getFlashBag()->add('error',$error);
                return $this->redirect($this->generateUrl('myapp_listing',array('house_id'=>$house_id)));
            }else{
                $form=$this->createForm(AddListingType::class, $house, array('method' => 'POST'));
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()){
                    $em=$this->getDoctrine()->getManager();
                    $em->flush();
                    $message = 'Your listing was updated successfully!';
                    $this->get('session')->getFlashBag()->add('message', $message);
                    return $this->redirect($this->generateUrl('myapp_listing',array('house_id'=>$house_id)));
                }
            }
        }
        return $this->render('@Myapp/Default/listing/updateListing.html.twig', array('form' => $form->createView(), 'title' => $house->getTitle()));
    }

    public function listingAction($house_id){
        $em=$this->getDoctrine()->getManager()->getRepository('MyappBundle:House');
        $house=$em->find($house_id);
        if($house==null) {
            $error = "This listing doesn't exist anymore.";
            $this->get('session')->getFlashBag()->add('error', $error);
            return $this->redirectToRoute('myapp_homepage');
        }
        $editor=$house->getUser();
        $active=$house->getIsActive();
        if (!$active){
            $error="This listing is not active!";
            $this->get('session')->getFlashBag()->add('error',$error);
        }

        return $this->render('@Myapp/Default/listing/listing.html.twig', array('house' =>$house, 'editor' =>$editor));

    }

    public function myListingsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user=='anon.'){
            $error="Please login to access this page.";
            $this->get('session')->getFlashBag()->add('error',$error);

            return $this->redirect($this->generateUrl('myapp_login'));
        }else {
            $em = $this->getDoctrine()->getManager()->getRepository('MyappBundle:House');
            $houses = $em->findBy(array('user' => $user));
            return $this->render('@Myapp/Default/listing/myListings.html.twig', array('houses' =>$houses));
        }
    }

    public function activateListingAction($house_id){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user=='anon.'){
            $error="Please login to access this page.";
            $this->get('session')->getFlashBag()->add('error',$error);

            return $this->redirect($this->generateUrl('myapp_login'));
        }else {
            $em = $this->getDoctrine()->getManager();
            $house = $em->getRepository('MyappBundle:House')->findOneBy(array('user' => $user, 'id'=>$house_id));
            $active=$house->getIsActive();
            $house->setIsActive(!$active);
            $em->flush();

            return $this->redirectToRoute('myapp_my_listings');
        }
    }

    public function deleteListingAction($house_id){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user=='anon.'){
            $error="Please login to access this page.";
            $this->get('session')->getFlashBag()->add('error',$error);

            return $this->redirect($this->generateUrl('myapp_login'));
        }else {
            $em = $this->getDoctrine()->getManager();
            $house = $em->getRepository('MyappBundle:House')->findOneBy(array('user' => $user, 'id'=>$house_id));
            $em->remove($house);
            $em->flush();

            return $this->redirectToRoute('myapp_my_listings');
        }
    }


}
