<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="admin", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="admin")
     */
    private $catrgories;

    public function __construct()
    {
        $this->catrgories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCatrgories(): Collection
    {
        return $this->catrgories;
    }

    public function addCatrgory(Category $catrgory): self
    {
        if (!$this->catrgories->contains($catrgory)) {
            $this->catrgories[] = $catrgory;
            $catrgory->setAdmin($this);
        }

        return $this;
    }

    public function removeCatrgory(Category $catrgory): self
    {
        if ($this->catrgories->contains($catrgory)) {
            $this->catrgories->removeElement($catrgory);
            // set the owning side to null (unless already changed)
            if ($catrgory->getAdmin() === $this) {
                $catrgory->setAdmin(null);
            }
        }

        return $this;
    }
}
