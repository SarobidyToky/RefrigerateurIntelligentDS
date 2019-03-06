<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ElementRepository")
 */
class Element
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
    private $quantiteSortie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="elements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sortie", inversedBy="elements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sortie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteSortie(): ?int
    {
        return $this->quantiteSortie;
    }

    public function setQuantiteSortie(int $quantiteSortie): self
    {
        $this->quantiteSortie = $quantiteSortie;

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

    public function getSortie(): ?Sortie
    {
        return $this->sortie;
    }

    public function setSortie(?Sortie $sortie): self
    {
        $this->sortie = $sortie;

        return $this;
    }
}
