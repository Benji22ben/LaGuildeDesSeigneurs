<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ORM\Table(name: "player")]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 16)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 32)]
    private $lastname;

    #[ORM\Column(type: 'string', length: 100)]
    private $email;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $mirian;

    #[ORM\Column(type: 'datetime')]
    private $creation;

    #[ORM\Column(type: 'datetime')]
    private $modification;

    #[ORM\Column(type: 'string', length: 40)]
    private $identifier;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Character::class)]
    private $characters;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMirian(): ?int
    {
        return $this->mirian;
    }

    public function setMirian(?int $mirian): self
    {
        $this->mirian = $mirian;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): self
    {
        $this->creation = $creation;

        return $this;
    }

    public function getModification(): ?\DateTimeInterface
    {
        return $this->modification;
    }

    public function setModification(\DateTimeInterface $modification): self
    {
        $this->modification = $modification;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    // public function toArray()
    // {
    //     return get_object_vars($this);
    // }


    /**
     * @return Collection|Character[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->setPlayer($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getPlayer() === $this) {
                $character->setPlayer(null);
            }
        }

        return $this;
    }
}
