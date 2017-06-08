<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Image;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Tag;
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
        $em = $this->getDoctrine()->getManager();
        $comment1 = new Comment();
        $comment2 = new Comment();
        $image = new Image();
        $article = new Article();
        $tags = new Tag();

        ///////////////////
        $image->setUrl("https://robohash.org/humanbooster" . rand(5, 50))
                ->setAlt("Mon robot #" . rand(5, 50));

        ///////////////////
        $article->setTitle('mon titre #' . rand(10, 50))
                ->setContent('blablablablabla #' . rand(10, 50))
                ->setImage($image);

        ///////////////////       
        $comment1->setContent("content #1");
        $comment1->setArticle($article);

        $comment2->setContent("content #2");
        $comment2->setArticle($article);

        ///////////////////   
        $tags->setTitle("php 7.0");
        $article->addTag($tags);

        //////////////////////////////////////////////////
        $em->persist($comment1);
        $em->persist($comment2);
        $em->persist($article);
        $em->flush();

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
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository('AppBundle:Article')->find($id);
            $em->remove($article);
            $em->flush();

            return $this->render('blog/delete.html.twig', ['id' => $id]);
        } else {
            throw new \Exception('id require !');
        }
    }

    /**
     * @Route("/read/{id}", name="read_blog")
     * requirements={"id": "\d+"})
     */
    public function readAction(Request $request, $id) {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository('AppBundle:Article')->find($id);
            dump($article);
            // replace this example code with whatever you , need
            return $this->render(
                            'blog/read.html.twig', ['article' => $article]); //$article->getComments();
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

    /**
     * @Route("/tag/articles/{id}", name="article_tag_blog",
     * requirements={"id": "\d+"})
     */
    public function tagAction($id) {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $tag = $em->getRepository('AppBundle:Tag')->find($id);

            return $this->render('blog/tags.html.twig', ['articles' => $tag->getArticles(), 'tag' => $tag]);
        } else {
            throw new \Exception('id require !');
        }
    }

}
