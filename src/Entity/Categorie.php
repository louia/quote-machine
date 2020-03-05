<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Util\Slugger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 * @Vich\Uploadable
 * @UniqueEntity("slug")
 * @ApiResource(
 *     collectionOperations={
 *     "get",
 *     "post"={"security"="is_granted('ROLE_ADMIN')"}
 * },
 *     itemOperations={
 *     "get",
 *     "delete"={"security"="is_granted('ROLE_ADMIN')"},
 *     "patch"={"security"="is_granted('ROLE_ADMIN')"},
 * },
 *
 *     normalizationContext={"groups"={"categorie_read"}},
 *     denormalizationContext={"groups"={"categorie_write"}}
 * )
 */
class Categorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"categorie_read","categorie_write"})
     */
    private $name;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="categorie", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", nullable=true,length=255)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups("categorie_read")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Citation", mappedBy="categorie")
     * @Groups({"categorie_read","categorie_write"})
     */
    private $citations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("categorie_read")
     */
    private $slug;

    public function __construct()
    {
        $this->citations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        $this->setSlug(Slugger::slugify($name));

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function countCitation()
    {
        return $this->citations->count();
    }

    /**
     * @return Collection|Citation[]
     */
    public function getCitations(): Collection
    {
        return $this->citations;
    }

    public function addCitation(Citation $citation): self
    {
        if (!$this->citations->contains($citation)) {
            $this->citations[] = $citation;
            $citation->addCategorie($this);
        }

        return $this;
    }

    public function removeCitation(Citation $citation): self
    {
        if ($this->citations->contains($citation)) {
            $this->citations->removeElement($citation);
            $citation->removeCategorie($this);
        }

        return $this;
    }

    public function __toString()
    {
        // to show the name of the Category in the select
        return $this->name;
        // to show the id of the Category in the select
        // return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
