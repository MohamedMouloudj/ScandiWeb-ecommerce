<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class UserRepository extends EntityRepository
{
    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
    }

    public function findWithPagination(int $limit, int $offset): array
    {
        return $this->createQueryBuilder('u')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find users by IDs and return them indexed by ID
     * This is optimized for DataLoader to efficiently batch load users
     */
    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $users = $this->createQueryBuilder('u')
            ->where('u.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();

        // Index results by ID for efficient lookup
        $indexedUsers = [];
        foreach ($users as $user) {
            $indexedUsers[$user->getId()] = $user;
        }

        return $indexedUsers;
    }

    /**
     * Find users with related data in a single query to avoid N+1 problem
     * Example of using joins instead of DataLoader for related data
     */
    public function findWithRelatedData(array $criteria = [], int $limit = 10, int $offset = 0): array
    {
        $qb = $this->createQueryBuilder('u')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('u.createdAt', 'DESC');

        // Example: If we had a posts relationship
        // $qb->leftJoin('u.posts', 'p')
        //    ->addSelect('p');

        // Apply criteria if provided
        foreach ($criteria as $field => $value) {
            $qb->andWhere("u.$field = :$field")
                ->setParameter($field, $value);
        }

        return $qb->getQuery()->getResult();
    }

    public function create(array $userData): User
    {
        $user = new User($userData['name'], $userData['email']);

        if (isset($userData['isActive'])) {
            $user->setActive($userData['isActive']);
        }

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }

    /**
     * Update a user with the given data
     */
    public function update(User $user, array $userData): User
    {
        if (isset($userData['name'])) {
            $user->setName($userData['name']);
        }

        if (isset($userData['email'])) {
            $user->setEmail($userData['email']);
        }

        if (isset($userData['isActive'])) {
            $user->setActive($userData['isActive']);
        }

        $this->getEntityManager()->flush();

        return $user;
    }
}
