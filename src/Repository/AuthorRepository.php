<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function save(Author $entity, bool $flush = false): void
{
    $this->getEntityManager()->persist($entity);

    if ($flush) {
        $this->getEntityManager()->flush();
    }
}

//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function ShowAllAuthorsQB(): mixed {
    return $this->createQueryBuilder(alias :'a')
                ->orderBy('a.username','ASC')
                ->andWhere('a.email LIKE :type')
                ->setParameter('type','%a%')
                ->getQuery()
                ->getResult();
}

public function findByBookCountRange(?int $min = null, ?int $max = null): array
{
    $qb = $this->createQueryBuilder('a');

    if ($min !== null) {
        $qb->andWhere('a.nb_books >= :min')
           ->setParameter('min', $min);
    }

    if ($max !== null) {
        $qb->andWhere('a.nb_books <= :max')
           ->setParameter('max', $max);
    }

    return $qb->orderBy('a.nb_books', 'DESC')
              ->getQuery()
              ->getResult();
}

public function deleteAuthorsWithNoBooks(): int
{
    return $this->createQueryBuilder('a')
        ->delete()
        ->where('a.nb_books = 0')
        ->getQuery()
        ->execute();
}

    
}
