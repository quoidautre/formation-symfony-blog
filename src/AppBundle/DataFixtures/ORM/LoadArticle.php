<?php
namespace AppBundle\DataFixtures\ORM;
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Image;
use AppBundle\Entity\Tag;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
/**
 * Created by PhpStorm.
 * User: fabrice
 * Date: 13/06/17
 * Time: 18:07
 */

class LoadArticle implements FixtureInterface {

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $title = [
                'Casino Royale',
                'Quantum of Solace',
                'Skyfall',
                'Spectre',
        ];
        $nbMovies = count($title);

        $content = [
            'Pour sa première mission, James Bond affronte le tout-puissant banquier privé du terrorisme international, Le Chiffre. Pour achever de le ruiner et démanteler le plus grand réseau criminel qui soit, Bond doit le battre lors d\'une partie de poker à haut risque au Casino Royale. La très belle Vesper, attachée au Trésor, l\'accompagne afin de veiller à ce que l\'agent 007 prenne soin de l\'argent du gouvernement britannique qui lui sert de mise, mais rien ne va se passer comme prévu',
            'Même s\'il lutte pour ne pas faire de sa dernière mission une affaire personnelle, James Bond est décidé à traquer ceux qui ont forcé Vesper à le trahir. En interrogeant Mr White, 007 et M apprennent que l\'organisation à laquelle il appartient est bien plus complexe et dangereuse que tout ce qu\'ils avaient imaginé...',
            'Lorsque la dernière mission de Bond tourne mal, plusieurs agents infiltrés se retrouvent exposés dans le monde entier. Le MI6 est attaqué, et M est obligée de relocaliser l’Agence. Ces événements ébranlent son autorité, et elle est remise en cause par Mallory, le nouveau président de l’ISC, le comité chargé du renseignement et de la sécurité',
            'Un message cryptique surgi du passé entraîne James Bond dans une mission très personnelle à Mexico puis à Rome, où il rencontre Lucia Sciarra, la très belle veuve d’un célèbre criminel. Bond réussit à infiltrer une réunion secrète révélant une redoutable organisation baptisée Spectre'
        ];

        $comments= [
            'The path of the righteous man is beset on all sides by the iniquities of the selfish and the tyranny of evil men.',
            'And I will strike down upon thee with great vengeance and furious anger those who would attempt to poison and destroy My brothers',
            'We swallow it too fast, we choke. We get some in our lungs, we drown.',
            'However unreal it may seem, we are connected, you and I. We\'re on the same curve, just on opposite ends.',
        ];

        $tags = [
             'guns', 'cars', 'vilains', 'bombs'
        ];

        for ($item = 0 ; $item < $nbMovies ; $item++) {
            $article = new Article();
            $image = new Image();
            $comment = new Comment();
            $comment2 = new Comment();
            $tag = new Tag();

            // ARTICLE /////////////////////////////////////////////////////////
            $article->setTitle($title[$item]);
            $article->setContent($content[$item]);
            $article->setDate(new \DateTime());
            $article->setPublicate(true);

            // IMAGE /////////////////////////////////////////////////////////
            $image->setUrl('https://robohash.org/'.str_replace(' ','-', strtolower($title[$item])).'.png?size=200x200');
            $image->setAlt(str_replace(' ','-', $title[$item]));

            $article->setImage($image);

            /// COMMENT //////////////////////////////////////////////////////
            $comment->setContent(date('d-m-Y H:i:s'). ' = '. $comments[$item]);
            $comment->setDate(new \DateTime());
            $comment->setArticle($article);

            $comment2->setContent(date('d-m-Y H:i:s'). ' = '. $comments[$item]);
            $comment2->setDate(new \DateTime());
            $comment2->setArticle($article);

            // TAG ///////////////////////////////////////////////////////////
            $tag->setTitle($tags[$item]);
            $article->addTag($tag);

            ///////////////////////////////////////////////////////////
            $manager->persist($comment);
            $manager->persist($comment2);
            $manager->persist($article);

            unset($article,$image,$comment,$comment2,$tag);

        }
        $manager->flush();

    }
}
