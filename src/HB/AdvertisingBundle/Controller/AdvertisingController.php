<?php

namespace HB\AdvertisingBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/advertising")
 */
class AdvertisingController extends Controller
{
    /**
     * @Route("/{page}", name="homepage_advertising",
     * defaults={"page":1},
     * requirements={"page": "\d+"})
     */
    public function indexAction()
    {
        return $this->render('HBAdvertisingBundle:advertising:index.html.twig');
    }
}
