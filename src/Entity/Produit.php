<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 * @Vich\Uploadable()
 */
class Produit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100)
     */
    private $filename;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="produit_image", fileNameProperty="filename")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $designation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Entree", mappedBy="produit")
     */
    private $entrees;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ListeCourse", mappedBy="produits")
     */
    private $listeCourses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Element", mappedBy="produit")
     */
    private $elements;

    public function __construct()
    {
        $this->entrees = new ArrayCollection();
        $this->listeCourses = new ArrayCollection();
        $this->elements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param null|string $filename
     * @return Produit
     */
    public function setFilename(?string $filename): Produit
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return null|File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param null|File $imageFile
     * @return Produit
     */
    public function setImageFile(?File $imageFile): Produit
    {
        $this->imageFile = $imageFile;
        if ( $this-> imageFile instanceof UploadedFile )
        {
            $this-> updated_at = new \ DateTime ( ' now ' );
        }
return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Entree[]
     */
    public function getEntrees(): Collection
    {
        return $this->entrees;
    }

    public function addEntree(Entree $entree): self
    {
        if (!$this->entrees->contains($entree)) {
            $this->entrees[] = $entree;
            $entree->setProduit($this);
        }

        return $this;
    }

    public function removeEntree(Entree $entree): self
    {
        if ($this->entrees->contains($entree)) {
            $this->entrees->removeElement($entree);
            // set the owning side to null (unless already changed)
            if ($entree->getProduit() === $this) {
                $entree->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ListeCourse[]
     */
    public function getListeCourses(): Collection
    {
        return $this->listeCourses;
    }

    public function addListeCourse(ListeCourse $listeCourse): self
    {
        if (!$this->listeCourses->contains($listeCourse)) {
            $this->listeCourses[] = $listeCourse;
            $listeCourse->setProduits($this);
        }

        return $this;
    }

    public function removeListeCourse(ListeCourse $listeCourse): self
    {
        if ($this->listeCourses->contains($listeCourse)) {
            $this->listeCourses->removeElement($listeCourse);
            // set the owning side to null (unless already changed)
            if ($listeCourse->getProduits() === $this) {
                $listeCourse->setProduits(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Element[]
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    public function addElement(Element $element): self
    {
        if (!$this->elements->contains($element)) {
            $this->elements[] = $element;
            $element->setProduit($this);
        }

        return $this;
    }

    public function removeElement(Element $element): self
    {
        if ($this->elements->contains($element)) {
            $this->elements->removeElement($element);
            // set the owning side to null (unless already changed)
            if ($element->getProduit() === $this) {
                $element->setProduit(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->designation;
    }

}
