<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
class Place
{
    // Tells doctrine to watch this entity
    #[ORM\Id]
    #[ORM\GeneratedValue]
    // describe the column in DB
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $way;

    #[ORM\Column(type: 'integer')]
    private $postCode;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    #[ORM\OneToMany(mappedBy: 'place', targetEntity: Event::class)]
    private $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function __toString() {
        return $this->getName(). " " . $this->getWay(). " " . $this->getPostCode() . " " . $this->getCity();
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

        return $this;
    }

    public function getWay(): ?string
    {
        return $this->way;
    }

    public function setWay(string $way): self
    {
        $this->way = $way;

        return $this;
    }

    public function getPostCode(): ?int
    {
        return $this->postCode;
    }

    public function setPostCode(int $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setPlace($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getPlace() === $this) {
                $event->setPlace(null);
            }
        }

        return $this;
    }
}
