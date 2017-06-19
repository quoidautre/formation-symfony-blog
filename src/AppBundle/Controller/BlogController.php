<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Image;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Tag;
use AppBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/blog")
 */
class BlogController extends Controller
{

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * @Route("/{page}", name="homepage_blog",
     * defaults={"page":1},
     * requirements={"page": "\d+"})
     */
    public function indexAction(Request $request, $page)
    {
        $articles = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Article')
            ->findAll();

        return $this->render('blog/index.html.twig', ['page' => $page, 'articles' => $articles]);
    }

    /**
     * @Route("/add", name="add_blog")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        /*$em = $this->getDoctrine()->getManager();
        $comment1 = new Comment();
        $comment2 = new Comment();
        $image = new Image();
        $article = new Article();
        $tags = new Tag();

        ///////////////////
        $image->setUrl("https://robohash.org/humanbooster" . mt_rand(5, 50))
            ->setAlt("Mon robot #" . mt_rand(5, 50));

        ///////////////////
        $article->setTitle('mon titre #' . mt_rand(10, 50))
            ->setContent('blablablablabla #' . mt_rand(10, 50))
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
        */
        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('title', null, ['label' => 'Saisissez un titre'])
            ->add('content')
            ->add('date',DateType::class, [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'html5'  => false])
            ->add('publicate', null, ['required' => false])
            ->add('submit',  SubmitType::class)
            ->getForm()
            ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Article bien enregistrée.');
        }

        return $this->render('blog/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="delete_blog",
     * requirements={"id": "\d+"})
     * @return ArticleRepository|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function deleteAction(Request $request, $id)
    {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $session = $this->get('session');

            try {
                $article = $em->getRepository('AppBundle:Article')->find($id);
                $em->remove($article);
                $em->flush();
                $typeAlert = 'success';
                $session->getFlashBag()->add('messageremovearticle', 'Article succefully remove.');
            } catch (\Exception $ex) {
                $typeAlert = 'danger';
                $session->getFlashBag()->add('messageremovearticle', 'An error occured during removing process !');
            }

            return $this->render('blog/delete.html.twig', ['id' => $id, 'typeAlert' => $typeAlert]);
        } else {
            throw new \Exception('id require!');
        }
    }

    /**
     * @Route("/read/{id}", name="read_blog")
     * requirements={"id": "\d+"})
     * @return ArticleRepository|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function readAction(Request $request, $id)
    {
        if ($id) {
            $article = $this->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:Article')
                ->getById($id);

            return $this->render(
                'blog/read.html.twig', ['article' => $article]);
        } else {
            throw new \Exception('id require!');
        }
    }

    /**
     * @Route("/update/{id}", name="update_blog",
     * requirements={"id": "\d+"})
     * @return |\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
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
     * @return ArticleRepository|\Symfony\Component\HttpFoundation\Response
     */
    public function lastAction($nb = 5)
    {
        if ($nb) {
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Article');
            $articles = $repository->getLast($nb);

            return $this->render(
                'blog/last.html.twig', ['articles' => $articles]);
        } else {
            throw new \Exception('id require!');
        }
    }

    /**
     * @Route("/tag/articles/{id}", name="article_tag_blog",
     * requirements={"id": "\d+"})
     */
    public function tagAction($id)
    {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $articles = $em->getRepository('AppBundle:Article')->getByTagId($id);
            $tag = $em->getRepository('AppBundle:Tag')->find($id);

            return $this->render('blog/tags.html.twig', ['articles' => $articles, 'tag' => $tag]);
        } else {
            throw new \Exception('id require!');
        }
    }

    /**
     * @param int $nb
     * @return ArticleRepository|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function yearsAction($nb = 5)
    {
        if ($nb) {
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Article');
            $years = $repository->getYears($nb);
            return $this->render('blog/years.html.twig', ['years' => $years]);
        } else {
            throw new \Exception('nb result years is require!');
        }
    }

    /**
     * @Route("/articles/year/{year}", name="article_year",
     * requirements={"year": "\d+"})
     * @param null $year
     * @return ArticleRepository|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function getByYear($year = null)
    {
        if ($year) {
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Article');
            $articles = $repository->getByYear($year);

            return $this->render('blog/byyear.html.twig', ['articles' => $articles]);
        } else {
            throw new \Exception('nb a year is require!');
        }
    }
}
