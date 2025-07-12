<?php

namespace App\DataLoader;

use GraphQL\Deferred;
use App\Entity\User;
use App\Database\DatabaseManager;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class UserLoader
{
    private DatabaseManager $db;
    private ?UserRepository $userRepository = null;
    private array $buffer = [];
    private bool $dispatched = false;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    private function getUserRepository(): UserRepository
    {
        if ($this->userRepository === null) {
            $this->userRepository = new UserRepository($this->db->getEntityManager(), new ClassMetadata(User::class));
        }
        return $this->userRepository;
    }

    public function load(int $id): Deferred
    {
        // Add ID to buffer
        $this->buffer[$id] = $id;

        // Return a deferred that will be resolved later
        return new Deferred(function () use ($id) {
            if (!$this->dispatched) {
                $this->dispatch();
            }

            return $this->getFromBuffer($id);
        });
    }

    public function loadMany(array $ids): Deferred
    {
        // Add all IDs to buffer
        foreach ($ids as $id) {
            $this->buffer[$id] = $id;
        }

        return new Deferred(function () use ($ids) {
            if (!$this->dispatched) {
                $this->dispatch();
            }

            return array_map([$this, 'getFromBuffer'], $ids);
        });
    }

    private function dispatch(): void
    {
        if (empty($this->buffer)) {
            return;
        }

        // Use the UserRepository's findByIds method
        $users = $this->getUserRepository()->findByIds(array_values($this->buffer));

        // Index users by ID
        $this->buffer = [];
        foreach ($users as $user) {
            $this->buffer[$user->getId()] = $user;
        }

        $this->dispatched = true;
    }

    private function getFromBuffer(int $id): ?User
    {
        return $this->buffer[$id] ?? null;
    }

    public function prime(User $user): void
    {
        $this->buffer[$user->getId()] = $user;
    }
}
