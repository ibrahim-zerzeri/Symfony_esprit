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
public function ShowAllAuthorsDQL(): mixed{
    $query = $this->getEntityManager()
    ->createQuery('SELECT a FROM App\Entity\Author a')
    ->getResult();
    return $query;
}

public function findByBookCountRange(?int $min = null, ?int $max = null): array
{
    $query = $this->getEntityManager()
             ->createQuery("SELECT a FROM App\Entity\Author a 
            WHERE a.nb_books >= $min 
              AND a.nb_books <= $max 
            ORDER BY a.nb_books DESC")
             ->getResult();
             return $query;
}

public function deleteAuthorsWithNoBooks(): int
{
    $query = $this->getEntityManager()
    ->createQuery('DELETE FROM App\Entity\Author a WHERE a.nb_books = 0')->getResult();
    return $query;
}

    
}
