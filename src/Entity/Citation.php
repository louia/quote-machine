<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CitationRepository")
 */
class Citation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5000)
     * @Assert\NotBlank
     * @Assert\Length(min = "3")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=5000)
     * @Assert\NotBlank
     * @Assert\Length(min = "3")
     */
    private $meta;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categorie", inversedBy="citations")
     */
    private $categorie;

    public function __construct()
    {
        $this->categorie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getMeta(): ?string
    {
        return $this->meta;
    }

    public function setMeta(string $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(Categorie $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie[] = $categorie;
        }

        return $this;
    }

    public function removeCategorie(Categorie $categorie): self
    {
        if ($this->categorie->contains($categorie)) {
            $this->categorie->removeElement($categorie);
        }

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->content;
        // to show the id of the Category in the select
        // return $this->id;
    }
}
