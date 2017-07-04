<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Form\ArticleType;
use AppBundle\Form\CommentType;
use AppBundle\Repository\ArticleRepository;
use AppBundle\Service\Excerpt;
use FOS\UserBundle\Model\User;
use function json_encode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \JMS\Serializer\SerializerBuilder;

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
     * @Route("/star", name="star_blog")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function starAction(Request $request)
    {
        $id= 10;
        $article = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Article')
            ->findOneBy(array('id' => $id));

        $form = $this->createForm('AppBundle\Form\StarType', new Star());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }
        return $this->render('/shop/product/star.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/{page}", name="homepage_blog",
     * defaults={"page":1},
     * requirements={"page": "\d+"})
     */
    public function indexAction(Request $request, $page, Excerpt $excerpt)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
        $limit = $this->getParameter('max_item_per_page');
        $offset = (int) (($page - 1 ) * $limit);

        $articles = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Article')
            ->getWithPaginator($offset, $limit);
            // SQL SELECT ... FROM ... WHERE ... LIMIT $offset, $limit
            //  A partir de l'enregistrement "$offset" et retourne "$limit" enregistrements

        $nbPages = ceil($articles->count() / $limit);

        foreach ($articles as $article) {
            $excerpt->setClass('myClass');
            $article->setExcerpt($excerpt->get($article));
        }

        return $this->render('blog/index.html.twig', [
            'page' => $page,
            'articles' => $articles,
            'nbPages' => $nbPages,
            'next'  => $page + 1,
            'prev'  => $page - 1
        ]);
    }

    /**
     * @Route("/add", name="add_blog")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article/*, ['id' => 10]*/);

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
     * @Security("is_granted('ROLE_SUPER_ADMIN') or user == article.getUser()")
     * @Route("/{slug}", name="read_blog")
     * requirements={"slug": "[a-zA-Z0-9-]"})
     * @return ArticleRepository|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function readAction(Request $request, $slug, Article $article)
    {
       // $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN',null,'Accès non autorisé');
        if ($slug) {
            $article = $this->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:Article')
                ->getBySlug($slug)
            ;

          //  dump($article);
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
     * @Route("/article/last/ajax/", name="ajax_article_last"),
     * @param Request $request
     * @return ArticleRepository|\Symfony\Component\HttpFoundation\Response
     */
    public function lastAjaxAction($nb = 2)
    {
        if ($nb) {
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Article');
            $articles = $repository->getLast($nb);
            return $this->render(
                'blog/last-ajax-modal.html.twig', ['articles' => $articles]);
        } else {
            throw new \Exception('last ajax id is require!');
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
     * @param int $id
     * @Route("/add/comment/{id}", name="article_add_comment"),
     * requirements={"id": "\d+"})
     * @return ArticleRepository|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addCommentsAction(Request $request, $id)
    {
        // * @Method({"POST"})
        if ((int)$id) {
            $comment = new Comment();
            $article = new Article();

            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository('AppBundle:Article')
                ->findOneBy(array('id' => $id));

            $form = $this->createForm(CommentType::class, $comment);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
                $session = $this->get('session');
                $comment->setArticle($article);

                $em->persist($comment);

                try {
                    $em->flush();

                } catch (\Exception $ex) {
                    $session->getFlashBag()->add('erreur', 'Le commentaire n\'est pas inséré !.');
                }

                $session->getFlashBag()->add('notice', 'Le commentaire bien enregistrée.');

                return $this->redirectToRoute('read_blog', ['id' => $id]);
            }

            return $this->render('blog/addComment.html.twig', [
                'form' => $form->createView(),
                'id' => $id
            ]);

        } else {
            throw new \Exception('an id article is required !');
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

    /**
     * @Route("/add/comment/ajax/", name="ajax_article_add_comment"),
     */
    public function ajaxAddComment(Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $dataJson = [
                'id' => (int) $request->get('id'),
                'content' => $request->get('content')
            ];
            $response = [];
            $response['comments']= null;
            $response['status'] = 'fail';

            $comment = new Comment();

            $newComment = null;

            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository('AppBundle:Article')
                ->findOneBy(array('id' => $dataJson['id']));

            $comment->setContent($dataJson['content']);
            $comment->setArticle($article);
/*
            $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
            $myUser= $serializer->deserialize($dataJson['user'], User::class,'json');
*/
            $myUser = $this->getUser();

            $comment->setUser($myUser);

            $em->persist($comment);

            try {
                $em->flush();

                $serializer = \JMS\Serializer\SerializerBuilder::create()->build();

                $response['status'] = 'success';
                $response['message'] = "Le commentaire a bien été ajouté";
                $response['comments'] = $serializer->serialize($article->getComments(), 'json') ;

            } catch (\Exception $ex) {
                $response['message'] = $ex->getMessage();
            }

            $response = new Response($response['comments']);
            $response->headers->set('Content-type','application/json');

            return $response;
        }
        return new JsonResponse('no results found', Response::HTTP_NOT_FOUND);
    }
}
