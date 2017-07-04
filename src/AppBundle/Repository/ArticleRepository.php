<?php

namespace AppBundle\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository {


    /**
     * @param $limit
     * @param $offset
     */
    public function getWithPaginator ($offset, $limit) {

        if ((int) $limit && (int) $offset >=0 ) {
            $qb =  $this->createQueryBuilder('a')
                ->leftJoin('a.image', 'i')->addSelect('i')
                ->leftJoin('a.tags', 't')->addSelect('t')
                ->andWhere('a.publicate = :publicate')
                ->setParameter(':publicate', 1)
                ->orderBy('a.date', 'DESC');

            // SQL ... LIMIT $offset, $limit
            if (false === is_null($offset))
                $qb->setFirstResult($offset);

            if (false === is_null($limit))
                $qb->setMaxResults($limit);

            $query =  $qb->getQuery();

            return new Paginator($query);
        }
        return null;
    }

    /**
     * 
     * @param type $id
     */
    public function getById($id) {
        if ($id) {
            return $this->createQueryBuilder('a')
                            ->leftJoin('a.image', 'i')->addSelect('i')
                            ->leftJoin('a.comments', 'c')->addSelect('c')
                            ->leftJoin('a.tags', 't')->addSelect('t')
                            ->where('a.id = :id')
                            ->setParameter(':id', (int) $id)
                            ->orderBy('a.date', 'DESC')
                            ->getQuery()
                            ->getOneOrNullResult();
        }
        return null;
    }

    /**
     * 
     * @param type $id
     * @return array
     */
    public function getByTagId($id) {
        if ($id) {
            return $this->createQueryBuilder('a')
                            ->leftJoin('a.tags', 't')->addSelect('t')
                            ->where('a.id = :id')
                            ->setParameter(':id', (int) $id)
                            ->andWhere('a.publicate = :publicate')
                            ->setParameter(':publicate', 1)
                            ->orderBy('a.date', 'DESC')
                            ->getQuery()
                            ->getResult();
        }
        return null;
    }

    /**
     * @param int $limit
     * @return array|null
     */
    public function getLast($limit = 5) {
        if ((int) $limit) {
            return $this->createQueryBuilder('a')
                            ->where('a.publicate = :publicate')
                            ->setParameter(':publicate', 1)
                            ->orderBy('a.date', 'DESC')
                            ->setMaxResults($limit)
                            ->getQuery()
                            ->getResult();
        }
        return null;
    }

    /**
     * @param int $limit
     * @return array|null
     */
    public function getYears($limit = 5) {
        if ((int) $limit) {
            return $this->createQueryBuilder('a')
                ->select('SUBSTRING(a.date,1,4)')
                ->distinct()
                ->orderBy('a.date', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }
        return null;
    }

    /**
     * @param $year
     * @param int $limit
     * @return array|null
     */
    public function getByYear($year, $limit = 20) {
        if ((int) $limit) {
            $debut = $year . '-01-01';
            $fin = $year . '-12-31';
            return $this->createQueryBuilder('a')
                ->where('a.date >= ?1 AND a.date<= ?2')
                ->setParameter(1, $debut)
                ->setParameter(2, $fin)
                ->andWhere('a.publicate = 1')
                ->orderBy('a.date', 'DESC')
                ->getQuery()
                ->getArrayResult();
        }
        return null;
    }

    /**
     * @param $year
     * @return array
     */
    public function getArticlesByYear($year) {
        $debut = $year . '-01-01';
        $fin = $year . '-12-31';
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.image', 'i')
            ->addSelect('i')
            ->leftJoin('a.tags', 't')
            ->addSelect('t')
            ->where('a.date >= ?1 AND a.date<= ?2')
            ->setParameter(1, $debut)
            ->setParameter(2, $fin)
            ->andWhere('a.publication = 1')
            ->orderBy('a.date', 'DESC');

        $query = $qb->getQuery();

        return $query->getArrayResult();
    }


    /**
     * @param $slug
     * @return mixed|null
     */
    public function getBySlug($slug) {
        if ($slug) {
            return $this->createQueryBuilder('a')
                ->leftJoin('a.image', 'i')->addSelect('i')
                ->leftJoin('a.comments', 'c')->addSelect('c')
                ->leftJoin('a.tags', 't')->addSelect('t')
                ->where('a.slug = :slug')
                ->setParameter(':slug', $slug)
                ->orderBy('a.date', 'DESC')
                ->getQuery()
                ->getOneOrNullResult() ;
        }
        return null;
    }
}
