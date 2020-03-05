<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CitationRepository")
 * @ApiResource(
 *     collectionOperations={
 *     "get",
 *     "post"={"security"="is_granted('ROLE_USER')"}
 * },
 *     itemOperations={
 *     "get",
 *     "delete"={"security"="is_granted('quotes_delete', object)"},
 *     "patch"={"security"="is_granted('quotes_edit', object)"},
 * },
 *     normalizationContext={"groups"={"quote_read"}},
 *     denormalizationContext={"groups"={"quote_write"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={"content": "partial", "meta": "partial", "author": "partial"})
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
     * @Groups({"quote_read","quote_write"})
     * @Assert\Length(min = "3")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=5000)
     * @Assert\NotBlank
     * @Groups({"quote_read", "quote_write"})
     * @Assert\Length(min = "3")
     */
    private $meta;

    /**
     * @Groups({"quote_read", "quote_write"})
     * @ORM\ManyToMany(targetEntity="App\Entity\Categorie", inversedBy="citations")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="citations")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("quote_read")
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("quote_read")
     */
    private $date_add;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="likers")
     */
    private $users;

    public function __construct()
    {
        $this->categorie = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->setDateAdd();
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

    public function __toString()
    {
        // to show the name of the Category in the select
        return $this->content;
        // to show the id of the Category in the select
        // return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;
        $this->setDateAdd();

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(): self
    {
        $this->date_add = new \DateTimeImmutable();

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addLiker($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeLiker($this);
        }

        return $this;
    }

    public function countLikes()
    {
        return $this->users->count();
    }
}
