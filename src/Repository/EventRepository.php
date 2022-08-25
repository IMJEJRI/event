<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findOneEventByIdWithKind($id)
    {
        $qb = $this->createQueryBuilder('event')
            ->select('event')
            ->leftJoin('event.kind', 'kind')
            ->addSelect('kind')
            ->where('event.id = :id')
            ->setParameter('id', $id)
            ->orderBy('event.datetime', 'DESC')
        ;

        $query = $qb->getQuery();
        dd($query);
        return $query->getOneOrNullResult();
    }

    public function findAllOrderByDate()
    {
        $qb = $this->createQueryBuilder('event')
            ->select('event')
            ->orderBy('event.datetime', 'DESC')
        ;
        $query = $qb->getQuery();

        return $query->getResult();
    }
}
