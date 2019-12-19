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
        return $this->findBy([], ['content' => 'ASC']);
    }

    public function findAllWithPaginator($paginator, $request)
    {
        $query = $this->createQueryBuilder('c')->orderBy('c.content')->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $pagination;
    }

    public function findAllbyContent($value, $paginator, $request)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c
        FROM App\Entity\Citation c
        WHERE c.content LIKE :name
        ORDER BY c.content '
        )->setParameter('name', '%'.$value.'%');

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $pagination;
    }

    public function getRandomquote()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c 
                FROM App\Entity\Citation c
                ORDER BY RAND()'
        )->setMaxResults(1);

        return $query->execute();
    }

    public function getbyCatg($catg)
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.categorie', 'cc')
            ->where('cc.name = :name')
            ->setParameter('name', $catg)
            ->orderBy('RAND()');

        $query = $qb->getQuery();

        return $query->execute();
    }
}
