<?php

namespace AppBundle\Repository;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class PaginatedEntityRepository extends EntityRepository
{
    public function createQuery($parameters)
    {
        $query = $this->createQueryBuilder('e');
        if (isset($parameters['filterbyfield']) && isset($parameters['pattern'])) {
            $this->filter($query, $parameters['filterbyfield'], $parameters['pattern']);
        }
        if (isset($parameters['sortbyfield']) && isset($parameters['order'])) {
            $this->sort($query, $parameters['sortbyfield'], $parameters['order']);
        }
        return $query;
    }

    public function paginate($query, $page, $rows)
    {
        $paginator = (new Pagerfanta(new DoctrineORMAdapter($query, false)))
            ->setMaxPerPage($rows)
            ->setCurrentPage($page);
        return $paginator;
    }

    protected function filter($query, $field, $pattern)
    {
        if ($field !== null && $pattern !== null) {
            $query
                ->where('e.'.$field . ' = :pattern')
                ->setParameter(':pattern', $pattern);
        }
        return $query;
    }
    protected function sort($query, $field, $order)
    {
        if ($field !== null && $order !== null) {
            $query->orderBy('e.'.$field, $order);
        }
        return $query;
    }
}