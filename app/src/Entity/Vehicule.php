<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $plaque_immatriculation = null;

    #[ORM\Column(length: 50)]
    private ?string $type_vehicule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photos = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_mise_en_circulation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlaqueImmatriculation(): ?string
    {
        return $this->plaque_immatriculation;
    }

    public function setPlaqueImmatriculation(string $plaque_immatriculation): static
    {
        $this->plaque_immatriculation = $plaque_immatriculation;

        return $this;
    }

    public function getTypeVehicule(): ?string
    {
        return $this->type_vehicule;
    }

    public function setTypeVehicule(string $type_vehicule): static
    {
        $this->type_vehicule = $type_vehicule;

        return $this;
    }

    public function getPhotos(): ?string
    {
        return $this->photos;
    }

    public function setPhotos(?string $photos): static
    {
        $this->photos = $photos;

        return $this;
    }

    public function getDateMiseEnCirculation(): ?\DateTimeInterface
    {
        return $this->date_mise_en_circulation;
    }

    public function setDateMiseEnCirculation(\DateTimeInterface $date_mise_en_circulation): static
    {
        $this->date_mise_en_circulation = $date_mise_en_circulation;

        return $this;
    }
}
