<?php

namespace App\Entity;

use App\Repository\MinistryRepository;
use App\Tenant\TenantAwareInterface;
use App\Tenant\TenantAwareTrait;
use Doctrine\ORM\Mapping as ORM;

/** Ministério / departamento da igreja (Louvor, Infantil, Diaconia, etc.). */
#[ORM\Entity(repositoryClass: MinistryRepository::class)]
#[ORM\Table(name: 'ministry')]
class Ministry implements TenantAwareInterface
{
    use TenantAwareTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    private ?Member $leader = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): static { $this->description = $d; return $this; }
    public function getLeader(): ?Member { return $this->leader; }
    public function setLeader(?Member $l): static { $this->leader = $l; return $this; }
    public function __toString(): string { return $this->name ?? 'Ministério'; }
}
