<?php

namespace App\Breakers\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: 'App\Breakers\Repository\GameRepository')]
#[ORM\Table(name: 'games')]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private int $fixtureId;

    #[ORM\Column(type: 'string', length: 100)]
    private string $countryName;

    #[ORM\Column(type: 'integer')]
    private int $leagueId;

    #[ORM\Column(type: 'string', length: 100)]
    private string $leagueName;

    #[ORM\Column(type: 'string', length: 9)]
    private string $seasonYear;

    #[ORM\Column(type: 'string', length: 50)]
    private string $seasonStage;

    #[ORM\Column(type: 'string', length: 1000, nullable: true)]
    private ?string $round = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $referee = null;

    #[ORM\Column(type: 'integer')]
    private int $homeTeamId;

    #[ORM\Column(type: 'string', length: 100)]
    private string $homeTeamName;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $homeTeamCoach = null;

    #[ORM\Column(type: 'integer')]
    private int $awayTeamId;

    #[ORM\Column(type: 'string', length: 100)]
    private string $awayTeamName;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $awayTeamCoach = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    private int $goalsHome;

    #[ORM\Column(type: 'integer')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    private int $goalsAway;

    #[ORM\Column(type: 'integer')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    private int $goalsHomeHt;

    #[ORM\Column(type: 'integer')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    private int $goalsAwayHt;

    public function __construct(
        int $fixtureId,
        string $countryName,
        int $leagueId,
        string $leagueName,
        string $seasonYear,
        string $seasonStage,
        \DateTimeInterface $date,
        int $homeTeamId,
        string $homeTeamName,
        int $awayTeamId,
        string $awayTeamName,
        int $goalsHome,
        int $goalsAway,
        int $goalsHomeHt,
        int $goalsAwayHt
    ) {
        $this->fixtureId = $fixtureId;
        $this->countryName = $countryName;
        $this->leagueId = $leagueId;
        $this->leagueName = $leagueName;
        $this->seasonYear = $seasonYear;
        $this->seasonStage = $seasonStage;
        $this->date = $date;
        $this->homeTeamId = $homeTeamId;
        $this->homeTeamName = $homeTeamName;
        $this->awayTeamId = $awayTeamId;
        $this->awayTeamName = $awayTeamName;
        $this->goalsHome = $goalsHome;
        $this->goalsAway = $goalsAway;
        $this->goalsHomeHt = $goalsHomeHt;
        $this->goalsAwayHt = $goalsAwayHt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFixtureId(): int
    {
        return $this->fixtureId;
    }

    public function setFixtureId(int $fixtureId): self
    {
        $this->fixtureId = $fixtureId;
        return $this;
    }

    public function getCountryName(): string
    {
        return $this->countryName;
    }

    public function setCountryName(string $countryName): self
    {
        $this->countryName = $countryName;
        return $this;
    }

    public function getLeagueId(): int
    {
        return $this->leagueId;
    }

    public function setLeagueId(int $leagueId): self
    {
        $this->leagueId = $leagueId;
        return $this;
    }

    public function getLeagueName(): string
    {
        return $this->leagueName;
    }

    public function setLeagueName(string $leagueName): self
    {
        $this->leagueName = $leagueName;
        return $this;
    }

    public function getSeasonYear(): string
    {
        return $this->seasonYear;
    }

    public function setSeasonYear(string $seasonYear): self
    {
        $this->seasonYear = $seasonYear;
        return $this;
    }

    public function getSeasonStage(): string
    {
        return $this->seasonStage;
    }

    public function setSeasonStage(string $seasonStage): self
    {
        $this->seasonStage = $seasonStage;
        return $this;
    }

    public function getRound(): ?string
    {
        return $this->round;
    }

    public function setRound(?string $round): self
    {
        $this->round = $round;
        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getReferee(): ?string
    {
        return $this->referee;
    }

    public function setReferee(?string $referee): self
    {
        $this->referee = $referee;
        return $this;
    }

    public function getHomeTeamId(): int
    {
        return $this->homeTeamId;
    }

    public function setHomeTeamId(int $homeTeamId): self
    {
        $this->homeTeamId = $homeTeamId;
        return $this;
    }

    public function getHomeTeamName(): string
    {
        return $this->homeTeamName;
    }

    public function setHomeTeamName(string $homeTeamName): self
    {
        $this->homeTeamName = $homeTeamName;
        return $this;
    }

    public function getHomeTeamCoach(): ?string
    {
        return $this->homeTeamCoach;
    }

    public function setHomeTeamCoach(?string $homeTeamCoach): self
    {
        $this->homeTeamCoach = $homeTeamCoach;
        return $this;
    }

    public function getAwayTeamId(): int
    {
        return $this->awayTeamId;
    }

    public function setAwayTeamId(int $awayTeamId): self
    {
        $this->awayTeamId = $awayTeamId;
        return $this;
    }

    public function getAwayTeamName(): string
    {
        return $this->awayTeamName;
    }

    public function setAwayTeamName(string $awayTeamName): self
    {
        $this->awayTeamName = $awayTeamName;
        return $this;
    }

    public function getAwayTeamCoach(): ?string
    {
        return $this->awayTeamCoach;
    }

    public function setAwayTeamCoach(?string $awayTeamCoach): self
    {
        $this->awayTeamCoach = $awayTeamCoach;
        return $this;
    }

    public function getGoalsHome(): int
    {
        return $this->goalsHome;
    }

    public function setGoalsHome(int $goalsHome): self
    {
        $this->goalsHome = $goalsHome;
        return $this;
    }

    public function getGoalsAway(): int
    {
        return $this->goalsAway;
    }

    public function setGoalsAway(int $goalsAway): self
    {
        $this->goalsAway = $goalsAway;
        return $this;
    }

    public function getGoalsHomeHt(): int
    {
        return $this->goalsHomeHt;
    }

    public function setGoalsHomeHt(int $goalsHomeHt): self
    {
        $this->goalsHomeHt = $goalsHomeHt;
        return $this;
    }

    public function getGoalsAwayHt(): int
    {
        return $this->goalsAwayHt;
    }

    public function setGoalsAwayHt(int $goalsAwayHt): self
    {
        $this->goalsAwayHt = $goalsAwayHt;
        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s vs %s (%s)', $this->homeTeamName, $this->awayTeamName, $this->date->format('Y-m-d H:i'));
    }
}