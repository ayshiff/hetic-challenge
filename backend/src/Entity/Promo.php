<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"teachers", "users", "promos", "cursus", "subjects"}},
 *     denormalizationContext={"groups"={"teachers", "users", "promos", "cursus", "subjects"}},
 *     collectionOperations={
 *         "get",
 *         "post"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\PromoRepository")
 */
class Promo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("promos")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups("promos")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cursus")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("promos")
     */
    private $cursus;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Teacher", inversedBy="promo")
     * @Groups("promos")
     */
    private $teachers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Subject", mappedBy="promo", orphanRemoval=true, cascade={"persist"})
     * @Groups("promos")
     */
    private $subjects;

    public function __construct()
    {
        $this->teachers = new ArrayCollection();
        $this->subjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getCursus(): ?cursus
    {
        return $this->cursus;
    }

    public function setCursus(?cursus $cursus): self
    {
        $this->cursus = $cursus;

        return $this;
    }

    /**
     * @return Collection|Teacher[]
     */
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    public function addTeacher(Teacher $promo): self
    {
        if (!$this->teachers->contains($promo)) {
            $this->teachers[] = $promo;
        }

        return $this;
    }

    public function removeTeacher(Teacher $promo): self
    {
        if ($this->teachers->contains($promo)) {
            $this->teachers->removeElement($promo);
        }

        return $this;
    }

    /**
     * @return Collection|Subject[]
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects[] = $subject;
            $subject->setPromo($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subjects->contains($subject)) {
            $this->subjects->removeElement($subject);
            // set the owning side to null (unless already changed)
            if ($subject->getPromo() === $this) {
                $subject->setPromo(null);
            }
        }

        return $this;
    }
}
