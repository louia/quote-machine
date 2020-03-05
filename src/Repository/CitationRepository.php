<?php

namespace App\Repository;

use App\Entity\Citation;
use App\Entity\User;
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

    public function findAllForCsv()
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.content, c.meta, cc.username author, c.date_add')
            ->join('c.author', 'cc');

        $query = $qb->getQuery();

        return $query->execute();
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

    public function findIfUseralreadyUseCatg($userId, $catgId)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT COUNT(c.id) as 'numberOfUsage'
                    FROM citation c, citation_categorie cc, user u
                    WHERE u.id=c.author_id AND cc.citation_id = c.id 
                    AND u.id= :userid AND cc.categorie_id = :catgid";

        $stmt = $conn->prepare($sql);
        $stmt->execute(['userid' => $userId, 'catgid' => $catgId]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetch();
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

    public function getLast5LikesByUser(User $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.users', 'cc')
            ->where('cc.id = :id')
            ->setParameter('id', $user->getId())
            ->setMaxResults(5);
        $query = $qb->getQuery();

        return $query->execute();
    }

    public function getOrderLike()
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c, COUNT(cc.id) AS HIDDEN nbLike')
            ->join('c.users', 'cc')
            ->groupBy('c.id')
            ->orderBy('nbLike', 'DESC');

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function getAllLikeByuser($user)
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.users', 'cc')
            ->where('cc.id = :id')
            ->setParameter('id', $user->getId());
        $query = $qb->getQuery();

        return $query->execute();
    }
}
