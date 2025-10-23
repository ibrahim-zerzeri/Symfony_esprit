<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findAll(): array
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.author', 'a')
            ->leftJoin('b.readers', 'r') // If you want to eager load readers
            ->addSelect('a', 'r')
            ->getQuery()
            ->getResult();
    }
    public function CountBooksRomance(): mixed
    {
        $query=$this->getEntityManager()
        ->createQuery("SELECT COUNT(b.id) FROM App\Entity\Book b WHERE b.category = 'Romance'")->getSingleScalarResult();
        return $query;
    }
    
    public function findBooksBetweenDates(): array
    {
       $query=$this->getEntityManager()
    
        ->createQuery("SELECT b FROM App\Entity\Book b 
                WHERE b.publicationDate BETWEEN '2014-01-01' AND '2018-12-31' 
                ORDER BY b.publicationDate ASC")->getResult();
                return $query;
    }
}