<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $idCategory
     * @return Product|null
     */
    public function getByCategory(Category $category)
    {
        if ($category) {
            return $this->createQueryBuilder('p')
                ->leftJoin('p.categories', 'c')->addSelect('c')
                ->where('c = :cat')
                ->setParameter(':cat', $category)
                ->orderBy('p.createdAt', 'ASC')
                ->getQuery()
                ->getResult();

        }
        return null;
    }
}
