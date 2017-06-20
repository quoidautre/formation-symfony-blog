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

class LoadProduct implements FixtureInterface {

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $title = [
                'Big Boss',
                'La fureur de vaincre',
                'La fureur du Dragon',
                'Opération Dragon',
                'Le jeu de la mort',
        ];
        $nbMovies = count($title);

        $content = [
            '(Big Boss) Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ',
            '(La fureur de vaincre) Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ',
            '(La fureur du Dragon) Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ',
            '(Opération Dragon) Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ',
            '(Le jeu de la mort) Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ',
  ];
        $category = [
             'action', 'thriller', 'violence', 'drama', 'adventure'
        ];

        for ($item = 0 ; $item < $nbMovies ; $item++) {
            $product = new Product();
            $category = new Category();

            // ARTICLE /////////////////////////////////////////////////////////
            $product->setTitle($title[$item]);
            $product->setContent($content[$item]);
            $product->setCreatedAt(new \DateTime());
            $product->setPrice($this->random_float(20,100));


            /// Category //////////////////////////////////////////////////////
            $category->setTitle($content[$item]);
            $product->setCategories();

            ///////////////////////////////////////////////////////////
            $manager->persist($product);
            $manager->persist($category);

            unset($product,$category);

        }
        $manager->flush();

    }

    private function random_float ($min,$max) {
        return ($min + lcg_value()*(abs($max - $min)));
    }
}
