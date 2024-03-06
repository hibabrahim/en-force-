<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez Remplir Ce Champs*")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez Remplir Ce Champs*")]
    private ?string $prix = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez Remplir Ce Champs*")]
    private $ressource = null;

    #[ORM\ManyToOne(inversedBy: 'produit_existant')]
    private ?Exposees $exposees = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getRessource()
    {
        return $this->ressource;
    }

    public function setRessource($ressource)
    {
        $this->ressource = $ressource;

        return $this;
    }

    public function getExposees(): ?Exposees
    {
        return $this->exposees;
    }

    public function setExposees(?Exposees $exposees): static
    {
        $this->exposees = $exposees;

        return $this;
    }
}
