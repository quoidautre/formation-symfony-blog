<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/blog")
 */
class BlogController extends Controller {

    /**
     * 
     */
    public function __construct() {
        //$this->em = $this->getDoctrine()->getManager();
        //  parent::__construct();
    }

    /**
     * @Route("/{page}", name="homepage_blog", 
     * defaults={"page":1}, 
     * requirements={"page": "\d+"})
     */
    public function indexAction(Request $request, $page) {
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Article');
        $articles = $repository->findAll();

        return $this->render('blog/index.html.twig', ['page' => $page, 'articles' => $articles]);
    }

    /**
     * @Route("/add", name="add_blog")
     */
    public function addAction(Request $request) {
        $image = new Image();
        $article = new Article();
        $image->setUrl("https://robohash.org/humanbooster" . rand(5, 50))
                ->setAlt("Mon robot #" . rand(5, 50));

        $article->setTitle('mon titre #' . rand(10, 50))
                ->setContent('blablablablabla #' . rand(10, 50))
                ->setImage($image);

        $this->em->persist($article);
        $this->em->flush();

        // replace this example code with whatever you , need
        $id = $article->getId();
        $route = 'read_blog';
        $parameters = ['id' => $id];

        return $this->redirectToRoute($route, $parameters);
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
     * @Route("/read/{id}", name="read_blog")
     * requirements={"id": "\d+"})
     */
    public function readAction(Request $request, $id) {
        /* $article = [
          "article" => [
          "id" => 20,
          "title" => "mon <u>titre</u>",
          "date" => new \DateTime]
          ]; */
        if ($id) {
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Article');
            $article = $repository->find($id);

            // replace this example code with whatever you , need
            return $this->render(
                            'blog/read.html.twig', ['article' => $article]);
        } else {
            throw new \Exception('id require !');
        }
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

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function lastAction($nb = 5) {
        if ($nb) {
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Article');
            $articles = $repository->findBy(
                    ['publicate' => true], ['date' => 'DESC'], $nb, 0
            );
            return $this->render(
                            'blog/last.html.twig', ['articles' => $articles]);
        } else {
            throw new \Exception('id require !');
        }
    }

}
