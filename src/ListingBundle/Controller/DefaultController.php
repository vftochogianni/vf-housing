<?php

namespace VFHousing\ListingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ListingBundle:Default:index.html.twig');
    }
}
