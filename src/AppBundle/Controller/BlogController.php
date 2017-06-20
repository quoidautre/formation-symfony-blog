<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use AppBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article, ['id' => 10]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
            $session = $this->get('session');
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);

            try {
                $em->flush();
            } catch (\Exception $ex) {
                $session->getFlashBag()->add('erreur', 'Article n\'est pas inséré !.');
            }

            $session->getFlashBag()->add('notice', 'Article bien enregistrée.');

            return $this->redirectToRoute('read_blog', ['id' => $article->getId()]);
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
        $article = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Article')
            ->findOneBy(array('id' => $id));

        $form = $this->createForm(ArticleType::class, $article/*, ['id' => $id]*/);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
            $session = $this->get('session');
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);

            try {
                $em->flush();
            } catch (\Exception $ex) {
                $session->getFlashBag()->add('erreur', 'Article n\'a pas été modifié !.');
            }

            $session->getFlashBag()->add('notice', 'Article bien modifié.');

            return $this->redirectToRoute('homepage_blog');
        }

        return $this->render('blog/update.html.twig', ['form' => $form->createView(),'article' => $article]);

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
        if (!empty($year)) {
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Article');
            $articles = $repository->getByYear($year);
            dump($articles);
            return $this->render('blog/byyear.html.twig', ['articles' => $articles]);
        } else {
            throw new \Exception('A year is require!');
        }
    }
}
