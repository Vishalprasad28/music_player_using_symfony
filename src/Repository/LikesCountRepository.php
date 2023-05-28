<?php

namespace App\Repository;

use App\Entity\LikesCount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LikesCount>
 *
 * @method LikesCount|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikesCount|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikesCount[]    findAll()
 * @method LikesCount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikesCountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikesCount::class);
    }

    public function save(LikesCount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LikesCount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Function to fetch all the song ids that has like Count greater than
     * a certain limit
     * 
     *   
     *     Returns the array of Likecount objects
     */
    public function findByTrending(int $likeCount, int $offset) {
        $queryBuilder = $this->createQueryBuilder('i');
        $query = $queryBuilder
        ->select('i.id')
        ->having('COUNT(i.id) >= :limit')
        ->groupBy('i.song')
        ->setMaxResults(9)
        ->setFirstResult($offset)
        ->setParameter('limit', $likeCount)
        ->getQuery();
        return $query->getResult();
            // ->where('COUNT(Likes.id) >= :limit')
            // ->groupBy('Likes.song_id')
            // ->setMaxResults(9)
            // ->setFirstResult($offset)
            // ->setParameter('limit', $likeCount)
            // ->getQuery()
            // ->getResult();
    }
//    /**
//     * @return LikesCount[] Returns an array of LikesCount objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LikesCount
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
