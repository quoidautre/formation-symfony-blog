<?php

namespace HB\AdvertisingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
    //php bin/console doctrine:generate:entity -entity=HBAdvertisingBundle
/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="HB\AdvertisingBundle\Repository\AdvertRepository")
 */
class Advert
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="HB\AdvertisingBundle\Entity\Advert_Image", cascade={"persist","remove"})
     */
    private $image;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set image
     *
     * @param \HB\AdvertisingBundle\Entity\Advert_Image $image
     *
     * @return Advert
     */
    public function setImage(\HB\AdvertisingBundle\Entity\Advert_Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \HB\AdvertisingBundle\Entity\Advert_Image
     */
    public function getImage()
    {
        return $this->image;
    }
}
