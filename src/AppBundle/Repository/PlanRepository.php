<?php

namespace AppBundle\Repository;

/**
 * PlanRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlanRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Liste des plan d'actions
     */
    public function findPlan($rubrique, $limit, $offset)
    {
        return $q = $this->createQueryBuilder('p')
                         ->where('p.rubrique LIKE :rubrique')
                         ->andWhere('p.statut = 1')
                         ->orderBy('p.id', 'DESC')
                         ->setFirstResult($offset)
                         ->setMaxResults($limit)
                         ->setParameter('rubrique', '%'.$rubrique.'%')
                         ->getQuery()->getResult();
            ;
    }
}
