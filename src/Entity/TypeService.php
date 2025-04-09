<?php

namespace App\Entity;

use App\Enums\TipoServicioAsignar;
use App\Repository\TypeServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeServiceRepository::class)]
class TypeService
{
    #[ORM\Column(type: Types::STRING, length: 255, enumType: TipoServicioAsignar::class)]
    private ?TipoServicioAsignar $asignTo = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;    

    #[ORM\Column]
    private ?bool $status = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAsignTo(): ?TipoServicioAsignar
    {
        return $this->asignTo;
    }

    public function setAsignTo(?TipoServicioAsignar $asignTo): self
    {
        $this->asignTo = $asignTo;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }
}
