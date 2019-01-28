<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntreeRepository")
 */
class Entree
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantiteEntree;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePeremption;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="entrees")
     */
    private $produit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteEntree(): ?int
    {
        return $this->quantiteEntree;
    }

    public function setQuantiteEntree(int $quantiteEntree): self
    {
        $this->quantiteEntree = $quantiteEntree;

        return $this;
    }

    public function getDatePeremption(): ?\DateTimeInterface
    {
        return $this->datePeremption;
    }

    public function setDatePeremption(\DateTimeInterface $datePeremption): self
    {
        $this->datePeremption = $datePeremption;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
}
