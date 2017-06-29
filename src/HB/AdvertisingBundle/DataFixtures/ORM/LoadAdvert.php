<?php
namespace HB\Advertising\DataFixtures\ORM;

use HB\AdvertisingBundle\Entity\Advert;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use HB\AdvertisingBundle\Entity\Advert_Image;

/**
 * Created by PhpStorm.
 * User: fabrice
 * Date: 13/06/17
 * Time: 18:07
 */
//php bin/console doctrine:fixtures:load --fixtures=src/HB/AdvertisingBundle/DataFixtures/ORM
class LoadAdvert implements FixtureInterface {

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $title = [
            'Développeur Symfony',
            'Développeur AngularJS',
            'Développeur JS',
            'Développeur Laravel',
        ];
        $nbAdvert= count($title);

        $content = [
            '1- On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L\'avantage du Lorem Ipsum sur un texte générique comme \'Du texte. Du texte. Du texte.\' est qu\'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour \'Lorem Ipsum\' vous conduira vers de nombreux sites qui n\'en sont encore qu\'à leur phase de construction',
            '2- On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L\'avantage du Lorem Ipsum sur un texte générique comme \'Du texte. Du texte. Du texte.\' est qu\'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour \'Lorem Ipsum\' vous conduira vers de nombreux sites qui n\'en sont encore qu\'à leur phase de construction',
            '3- On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L\'avantage du Lorem Ipsum sur un texte générique comme \'Du texte. Du texte. Du texte.\' est qu\'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour \'Lorem Ipsum\' vous conduira vers de nombreux sites qui n\'en sont encore qu\'à leur phase de construction',
            '4- On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L\'avantage du Lorem Ipsum sur un texte générique comme \'Du texte. Du texte. Du texte.\' est qu\'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour \'Lorem Ipsum\' vous conduira vers de nombreux sites qui n\'en sont encore qu\'à leur phase de construction',
            ];

        for ($item = 0 ; $item < $nbAdvert ; $item++) {
            $advert = new Advert();
            $image = new Advert_Image();

            // ADVERT /////////////////////////////////////////////////////////
            $advert->setTitle($title[$item]);
            $advert->setContent($content[$item]);

            // IMAGE /////////////////////////////////////////////////////////
            $image->setUrl('https://loremflickr.com/320/240');
            $image->setAlt(str_replace(' ','-', $title[$item]));

            $advert->setImage($image);

            $manager->persist($advert);

            unset($advert);
        }
        $manager->flush();
    }
}
