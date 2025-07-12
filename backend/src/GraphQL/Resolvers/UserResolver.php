<?php

namespace App\GraphQL\Resolvers;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Deferred;
use App\Entity\User;
use App\Database\DatabaseManager;
use App\Error\ValidationException;
use App\Error\NotFoundException;
use App\Repository\UserRepository;

class UserResolver
{
    private static ?DatabaseManager $db = null;
    private static ?UserRepository $userRepository = null;

    private static function getDB(): DatabaseManager
    {
        if (self::$db === null) {
            self::$db = new DatabaseManager();
        }
        return self::$db;
    }

    private static function getUserRepository(): UserRepository
    {
        if (self::$userRepository === null) {
            self::$userRepository = self::getDB()->getEntityManager()->getRepository(User::class);
        }
        return self::$userRepository;
    }

    // Query: Get single user
    public static function getUser($root, array $args, $context, ResolveInfo $info)
    {
        $id = (int) $args['id'];

        if ($id <= 0) {
            throw new ValidationException("Invalid user ID: {$id}");
        }

        error_log("User ID: " . $id);


        // Use the DataLoader from context
        return $context['userLoader']->load($id)->then(function ($user) use ($id) {
            if (!$user) {
                throw new NotFoundException("User with ID {$id} not found");
            }
            // Debug: Check what we're getting
            error_log("User ID: " . $user->getId());
            error_log("User Name: " . ($user->getName() ?? 'NULL'));
            error_log("User Email: " . ($user->getEmail() ?? 'NULL'));

            return $user;
        });
    }

    // Query: Get multiple users
    public static function getUsers($root, array $args, $context, ResolveInfo $info): array
    {
        $limit = $args['limit'] ?? 10;
        $offset = $args['offset'] ?? 0;

        // Use the repository method instead of direct EntityManager
        $users = self::getUserRepository()->findWithPagination($limit, $offset);

        // Prime the DataLoader with fetched users to avoid additional queries
        foreach ($users as $user) {
            $context['userLoader']->prime($user);
        }

        return $users;
    }

    // Mutation: Create user
    public static function createUser($root, array $args, $context, ResolveInfo $info): User
    {
        $input = $args['input'];

        // Validate email uniqueness
        $existing = self::getUserRepository()->findOneBy(['email' => $input['email']]);

        if ($existing) {
            throw new \Exception("User with email {$input['email']} already exists");
        }

        // Use the repository method to create user
        $user = self::getUserRepository()->create($input);

        // Prime the DataLoader with the newly created user
        $context['userLoader']->prime($user);

        return $user;
    }

    // Mutation: Update user
    public static function updateUser($root, array $args, $context, ResolveInfo $info): ?User
    {
        $id = (int) $args['id'];
        $input = $args['input'];

        $user = self::getUserRepository()->find($id);

        if (!$user) {
            throw new \Exception("User with ID {$id} not found");
        }

        if (isset($input['email']) && $input['email'] !== $user->getEmail()) {
            // Check email uniqueness
            $existing = self::getUserRepository()->findOneBy(['email' => $input['email']]);

            if ($existing && $existing->getId() !== $user->getId()) {
                throw new \Exception("Email {$input['email']} is already taken");
            }
        }

        // Use the repository method to update user
        $user = self::getUserRepository()->update($user, $input);

        // Update the DataLoader with the modified user
        $context['userLoader']->prime($user);

        return $user;
    }

    // Mutation: Delete user
    public static function deleteUser($root, array $args, $context, ResolveInfo $info): bool
    {
        $id = (int) $args['id'];

        $user = self::getUserRepository()->find($id);

        if (!$user) {
            throw new \Exception("User with ID {$id} not found");
        }

        $em = self::getDB()->getEntityManager();
        $em->remove($user);
        $em->flush();

        return true;
    }
}
