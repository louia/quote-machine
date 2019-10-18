<?php

namespace App\Repository;

use App\Entity\Citation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Citation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Citation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Citation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Citation::class);
    }

    /**
     * @return Citation[]|array
     */
    public function findAll()
    {
        return $this->findBy(array(), array('content' => 'ASC'));
    }

    // /**
    //  * @return Citation[] Returns an array of Citation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Citation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getCatgWithCitation(){
//        $qb = $entityManager->createQueryBuilder();
//        $qb->select('count(account.id)');
//        $qb->from('ZaysoCoreBundle:Account','account');
//
//        $count = $qb->getQuery()->getSingleScalarResult();


        $qb = $this->createQueryBuilder('c')
            ->Select('COUNT(c) as count, cc.name')
            ->innerJoin('App\Entity\Categorie', 'cc')
            ->addGroupBy('cc.id');



        $query = $qb->getQuery();

        return $query->execute();
    }
    public function findAllbyContent($value): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c
        FROM App\Entity\Citation c
        WHERE c.content LIKE :name
        ORDER BY c.content '
        )->setParameter('name', '%'.$value.'%');

        // returns an array of Product objects
        return $query->execute();
    }

    public function getRandomquote(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c 
                FROM App\Entity\Citation c
                ORDER BY RAND()'
        )->setMaxResults(1);

        // returns an array of Product objects
        return $query->execute();
    }
}
