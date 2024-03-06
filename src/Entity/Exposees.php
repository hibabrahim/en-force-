<?php

namespace App\Entity;

use App\Repository\ExposeesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use App\Entity\Produits;

#[ORM\Entity(repositoryClass: ExposeesRepository::class)]
class Exposees
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez Remplir Ce Champs*")]
    private ?string $nom_e = null;

    #[Assert\NotBlank(message: "Veuillez Remplir La Date")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_debut = null;

    #[Assert\NotBlank(message: "Veuillez Remplir La Date")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez Remplir Ce Champs*")]
    private $image_exposees = null;

    #[ORM\OneToMany(targetEntity: Produits::class, mappedBy: 'exposees')]
    private Collection $produit_existant;

    public function __construct()
    { 
        $this->date_debut = new \DateTime('now');
        $this->date_fin = new \DateTime('now');
        $this->produit_existant = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomE(): ?string
    {
        return $this->nom_e;
    }

    public function setNomE(string $nom_e): static
    {
        $this->nom_e = $nom_e;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getImageExposees()
    {
        return $this->image_exposees;
    }

    public function setImageExposees($image_exposees)
    {
        $this->image_exposees = $image_exposees;

        return $this;
    }

    /**
     * @return Collection<int, Produits>
     */
    public function getProduitExistant(): Collection
    {
        return $this->produit_existant;
    }

    public function addProduitExistant(Produits $produitExistant): static
    {
        if (!$this->produit_existant->contains($produitExistant)) {
            $this->produit_existant->add($produitExistant);
            $produitExistant->setExposees($this);
        }

        return $this;
    }

    public function removeProduitExistant(Produits $produitExistant): static
    {
        if ($this->produit_existant->removeElement($produitExistant)) {
            // set the owning side to null (unless already changed)
            if ($produitExistant->getExposees() === $this) {
                $produitExistant->setExposees(null);
            }
        }

        return $this;
    }
}
