<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SiteRepository extends EntityRepository
{
    /**
     * @param string $name
     * @param Framework $framework
     * @return array
     */
    public function search( $name = null, Framework $framework = null)
    {

        $qb = $this->createQueryBuilder('s');
        if ($name) {
            $qb->andWhere('s.name LIKE :name')
                ->setParameter('name', "%" . $name . "%");
        }
        if ($framework) {
            $qb->andWhere('s.framework = :framework')
                ->setParameter('framework', $framework);
        }
        return $qb->getQuery()->getResult();
    }
}
