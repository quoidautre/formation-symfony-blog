<?php

namespace HB\AdvertisingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('HBAdvertisingBundle:Default:index.html.twig');
    }
}
