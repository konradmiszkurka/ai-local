<?php

namespace App\Compartments\Entity;

use App\Compartments\Repository\ClubRangeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClubRangeRepository::class)]
#[ORM\Table(name: 'club_ranges')]
class ClubRange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $clubName;

    #[ORM\Column(type: 'string', length: 100)]
    private string $league;

    #[ORM\Column(type: 'json')]
    private array $rangeSeries;

    public function __construct(string $clubName, string $league, array $rangeSeries)
    {
        $this->clubName = $clubName;
        $this->league = $league;
        $this->rangeSeries = $rangeSeries;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClubName(): string
    {
        return $this->clubName;
    }

    public function setClubName(string $clubName): self
    {
        $this->clubName = $clubName;
        return $this;
    }

    public function getLeague(): string
    {
        return $this->league;
    }

    public function setLeague(string $league): self
    {
        $this->league = $league;
        return $this;
    }

    public function getRangeSeries(): array
    {
        return $this->rangeSeries;
    }

    public function setRangeSeries(array $rangeSeries): self
    {
        $this->rangeSeries = $rangeSeries;
        return $this;
    }
}