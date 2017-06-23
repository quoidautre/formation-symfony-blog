<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/shop")
 */
class ShopController extends Controller
{
    /**
     * @Route("/", name="homepage_shop")
     */
    public function indexAction(Request $request)
    {
        return $this->render('shop/index.html.twig');
    }
}
