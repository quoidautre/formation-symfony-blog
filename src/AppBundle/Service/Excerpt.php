<?php
/**
 * Created by PhpStorm.
 * User: Human Booster
 * Date: 26/06/2017
 * Time: 13:35
 */

namespace AppBundle\Service;


use AppBundle\Entity\Article;

class Excerpt
{
    public $txtExcerpt = '';
    public $limitExcerpt;
    public $route;

   public function __construct($maxLength, $router)
   {
       $this->limitExcerpt = (int)$maxLength;
       $this->route = $router;
       $this->classHTML = '';
   }

    public function get(Article $article)
    {
        $this->txtExcerpt = strip_tags(trim($article->getContent()));

        if (mb_strlen($this->txtExcerpt) >= $this->limitExcerpt) {
            $this->txtExcerpt = substr($this->txtExcerpt, 0, $this->limitExcerpt);
            $urlRoute = $this->getGeneratedRoute('read_blog',$article->getId());
            $this->txtExcerpt .= ' ['. '<a class="'.$this->getClass().'" href="'.$urlRoute.'">Voir la suite</a>]';
        }



        return $this->txtExcerpt;
    }

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function getGeneratedRoute($name, $id) {
       return $this->route->generate($name,['id' => $id]);
    }

    public function getClass() {
        return $this->classHTML;
    }
    public function setClass($class) {
        $this->classHTML = $class;
    }
}
