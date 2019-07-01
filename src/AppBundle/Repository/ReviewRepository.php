<?php


namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Review;
use Exception;
use Doctrine\ORM\Tools\Pagination\Paginator;


class ReviewRepository extends EntityRepository
{

    const PER_PAGE_LIMIT = 5;

    public function findReview($q, $ratings = [],$page = 1, $perPage = self::PER_PAGE_LIMIT)
    {

//        $repository = $this->getEntityManager()->getRepository(Review::class);
        $query = $this->createQueryBuilder('r')
            ->where('r.message LIKE :q')
            ->setParameter('q','%'.$q.'%');

        if (!empty($ratings))
        {
            $query->andWhere('r.rating in (:ratings)')
                ->setParameter('ratings',$ratings);
        }
        $offset = ($page - 1) * $perPage;
        $query->setFirstResult($offset)
            ->setMaxResults($perPage)
            ->getQuery();

        return $paginator = new Paginator($query, $fetchJoinCollection = true);

    }

    public function addReview($data)
    {
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {
            $new_review = new Review();
            $new_review->setFullName($data['full_name']);
            $new_review->setEmail($data['email']);
            $new_review->setMessage($data['message']);
            $new_review->setRating($data['rating']);
            $em->persist($new_review);
            $em->flush();

        } catch (Exception $e) {
            $em->getConnection()->rollBack();
            return $e->getMessage();
        }
        $em->getConnection()->commit();

        return $new_review;
    }

    public function statistics()
    {
        $em = $this->getEntityManager();

        $stats = $this->createQueryBuilder('r','r.rating')
            ->select('count(r.id) as total,r.rating')
            ->groupBy('r.rating')
            ->getQuery()
            ->getResult();

        $raw = 'SELECT sum(rating) as total_rating,count(*) as total_records FROM reviews';

        $statement = $em->getConnection()->prepare($raw);
        $statement->execute();
        $result = $statement->fetch();

        return [
            'stats' => $stats,
            'total_rating' => $result['total_rating'],
            'total_records' => $result['total_records']
        ];
    }
}