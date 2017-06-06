<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/blog")
 */
class BlogController extends Controller {

    /**
     * @Route("/{page}", name="homepage_blog", 
     * defaults={"page":1}, 
     * requirements={"page": "\d+"})
     */
    public function indexAction(Request $request, $page) {
        // replace this example code with whatever you , need
        return $this->render('blog/index.html.twig', ['page' => $page]);
    }

    ////////////////////////
    /**
     * @Route("/add", name="add_blog")
     */
    public function addAction(Request $request) {
        // replace this example code with whatever you , need
        return $this->render('blog/add.html.twig');
    }

    /**
     * @Route("/delete/{id}", name="delete_blog", 
     * requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request, $id) {
        // replace this example code with whatever you , need
        return $this->render('blog/delete.html.twig', ['id' => $id]);
    }

    /**
     * @Route("/read", name="read_blog")
     */
    public function readAction(Request $request) {
        // replace this example code with whatever you , need
        return $this->render('blog/read.html.twig');
    }

    /**
     * @Route("/update/{id}", name="update_blog",
     * requirements={"id": "\d+"})
     */
    public function updateAction(Request $request, $id) {
        /* echo '<pre>';
          var_dump($request);
          echo '</pre>';
         */
        // replace this example code with whatever you , need
        return $this->render('blog/update.html.twig', ['id' => $id]);
    }

}
