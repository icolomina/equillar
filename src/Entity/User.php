<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Entity;

use App\Entity\Contract\UserContract;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EquatableInterface
{
    public const ROLE_FINANCIAL_ENTITY = 'ROLE_COMPANY';
    public const ROLE_SAVER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var Collection<int, UserContract>
     */
    #[ORM\OneToMany(targetEntity: UserContract::class, mappedBy: 'usr', orphanRemoval: true)]
    private Collection $contracts;

    /**
     * @var Collection<int, UserWallet>
     */
    #[ORM\OneToMany(targetEntity: UserWallet::class, mappedBy: 'usr', orphanRemoval: true)]
    private Collection $userWallets;

    public function __construct()
    {
        $this->contracts = new ArrayCollection();
        $this->userWallets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getUserRoleType(): string
    {
        return match (true) {
            $this->isAdmin() => 'Administrator',
            $this->isSaver() => 'Investor',
            default => 'Company',
        };
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function isSaver(): bool
    {
        return in_array(self::ROLE_SAVER, $this->getRoles());
    }

    public function isCompany(): bool
    {
        return in_array(self::ROLE_FINANCIAL_ENTITY, $this->getRoles());
    }

    public function isAdmin(): bool
    {
        return in_array(self::ROLE_ADMIN, $this->getRoles());
    }

    /**
     * @return Collection<int, UserContract>
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function addContract(UserContract $contract): static
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts->add($contract);
            $contract->setUsr($this);
        }

        return $this;
    }

    public function removeContract(UserContract $contract): static
    {
        if ($this->contracts->removeElement($contract)) {
            // set the owning side to null (unless already changed)
            if ($contract->getUsr() === $this) {
                $contract->setUsr(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserWallet>
     */
    public function getUserWallets(): Collection
    {
        return $this->userWallets;
    }

    public function addUserWallet(UserWallet $userWallet): static
    {
        if (!$this->userWallets->contains($userWallet)) {
            $this->userWallets->add($userWallet);
            $userWallet->setUsr($this);
        }

        return $this;
    }

    public function removeUserWallet(UserWallet $userWallet): static
    {
        if ($this->userWallets->removeElement($userWallet)) {
            // set the owning side to null (unless already changed)
            if ($userWallet->getUsr() === $this) {
                $userWallet->setUsr(null);
            }
        }

        return $this;
    }

    public function isEqualTo(UserInterface $user): bool
    {
        return $user->getUserIdentifier() === $this->getUserIdentifier();
    }
}
