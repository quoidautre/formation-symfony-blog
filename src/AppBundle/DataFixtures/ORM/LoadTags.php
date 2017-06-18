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

class LoadTags implements FixtureInterface {

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $tags = ['guns', 'cars', 'vilains', 'bombs'];

        foreach ($tags as $tag) {
            $aTag = new Tag();
            $aTag->setTitle($tag);

            $manager->persist($aTag);
        }
        $manager->flush();
    }
}
