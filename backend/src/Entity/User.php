<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    // Constructor
    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->createdAt = new \DateTime();
    }

    // Getters and Setters
    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    public function isActive(): bool
    {
        return $this->isActive;
    }
    public function setActive(bool $active): void
    {
        $this->isActive = $active;
    }
}
