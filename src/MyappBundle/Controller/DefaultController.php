<?php

namespace MyappBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyappBundle\Entity\House;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em=$this->getDoctrine()->getManager()->getRepository('MyappBundle:House');
        $latestHouses=$em->findBy(array('isSponsored'=>false, 'isActive'=>true), array('createdAt'=> 'DESC'),4);
        $sponsoredHouses=$em->findBy(array('isSponsored'=>true, 'isActive'=>true), array('createdAt'=> 'DESC'),4);
        return $this->render('MyappBundle:Default:index.html.twig', array('latestHouses'=>$latestHouses, 'sponsoredHouses' => $sponsoredHouses));
    }
}
