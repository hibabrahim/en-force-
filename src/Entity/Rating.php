<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ORM\Table(name: '`rating`')]
class Rating {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type : 'integer')]
    private ?int $note=null;

    #[ORM\Column(type : 'integer')]
    private ?int $idAdmin=null;
    #[ORM\Column(type : 'integer')]
    private ?int $user=null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAdmin(): ?int
    {
        return $this->idAdmin;
    }

    public function setIdAdmin(?int $id): self
    {
        $this->idAdmin = $id;

        return $this;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(?int $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function getNote(): ?int
    {
        return $this->note;
    }
    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }

}