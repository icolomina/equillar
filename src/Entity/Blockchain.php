<?php

namespace App\Entity;

use App\Repository\BlockchainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlockchainRepository::class)]
class Blockchain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $infoUrl = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, BlockchainNetwork>
     */
    #[ORM\OneToMany(targetEntity: BlockchainNetwork::class, mappedBy: 'blockchain')]
    private Collection $networks;

    #[ORM\Column(length: 50)]
    private ?string $label = null;

    public function __construct()
    {
        $this->networks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getInfoUrl(): ?string
    {
        return $this->infoUrl;
    }

    public function setInfoUrl(string $infoUrl): static
    {
        $this->infoUrl = $infoUrl;

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

    /**
     * @return Collection<int, BlockchainNetwork>
     */
    public function getNetworks(): Collection
    {
        return $this->networks;
    }

    public function addNetwork(BlockchainNetwork $network): static
    {
        if (!$this->networks->contains($network)) {
            $this->networks->add($network);
            $network->setBlockchain($this);
        }

        return $this;
    }

    public function removeNetwork(BlockchainNetwork $network): static
    {
        if ($this->networks->removeElement($network)) {
            // set the owning side to null (unless already changed)
            if ($network->getBlockchain() === $this) {
                $network->setBlockchain(null);
            }
        }

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }
}
