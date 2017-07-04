<?php
/**
 * Created by PhpStorm.
 * User: Human Booster
 * Date: 26/06/2017
 * Time: 13:35
 */

namespace AppBundle\Twig;
use AppBundle\Entity\Article;

class ExcerptTwig extends \Twig_Extension
{
    public $txtExcerpt = '';
    public $limitExcerpt;
    public $route;

   public function __construct($maxLength, $router, $next)
   {
       $this->limitExcerpt = (int)$maxLength;
       $this->route = $router;
       $this->classHTML = '';
       $this->next = $next;
   }

    public function get(Article $article)
    {
        $this->txtExcerpt = strip_tags(trim($article->getContent()));

        if (mb_strlen($this->txtExcerpt) >= $this->limitExcerpt) {
            $this->txtExcerpt = substr($this->txtExcerpt, 0, $this->limitExcerpt);
            //$urlRoute = $this->getGeneratedRoute('read_blog',$article->getId());
            $urlRoute = $this->getGeneratedRoute('read_blog',$article->getSlug());
            $this->txtExcerpt .= '&nbsp;<a class="'.$this->getClass().'" href="'.$urlRoute.'">'.$this->next.'</a>';
        }

        return $this->txtExcerpt;
    }

    /**
     * @return array
     */
    public function getFunctions() {
        return array(new \Twig_SimpleFunction('ExcerptTwig', [$this, 'get']));
    }
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('ex', array($this, 'get')),
        );
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function getGeneratedRoute($name, $slug) {
       return $this->route->generate($name,['slug' => $slug]);
    }

    public function getClass() {
        return $this->classHTML;
    }
    public function setClass($class) {
        $this->classHTML = $class;
    }
}
