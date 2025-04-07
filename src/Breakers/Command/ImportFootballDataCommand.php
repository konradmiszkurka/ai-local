<?php

namespace App\Breakers\Command;

use App\Breakers\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-football-data',
    description: 'Imports football data from an XLSX file into the database'
)]
class ImportFootballDataCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private string $publicDir;

    public function __construct(EntityManagerInterface $entityManager, string $publicDir)
    {
        $this->entityManager = $entityManager;
        $this->publicDir = $publicDir;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Name of the XLSX file in the public directory (e.g., football_data.xlsx)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fileName = $input->getArgument('file');
        $filePath = $this->publicDir . '/' . $fileName;

        if (!file_exists($filePath)) {
            $io->error("File not found: $filePath");
            return Command::FAILURE;
        }

        $io->info("Starting import from $filePath");

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $header = $worksheet->rangeToArray('A1:' . $worksheet->getHighestColumn() . '1', null, true, false)[0];
        $header = array_map('trim', $header);

        $imported = 0;
        $rowIndex = 2;

        while ($rowIndex <= $highestRow) {
            try {
                $row = $worksheet->rangeToArray('A' . $rowIndex . ':' . $worksheet->getHighestColumn() . $rowIndex, null, true, false)[0];
                $data = array_combine($header, $row);

                $fixtureId = (int) $data['fixture_id'];
                $countryName = trim($data['country_name']);
                $leagueId = (int) $data['league_id'];
                $leagueName = trim($data['league_name']);
                $seasonYear = trim($data['season_year']);
                $seasonStage = trim($data['season_stage']);

                $dateValue = $data['date'];
                if (is_numeric($dateValue)) {
                    $date = Date::excelToDateTimeObject($dateValue);
                } else {
                    $date = new \DateTime($dateValue);
                }

                $homeTeamId = (int) $data['home_team_id'];
                $homeTeamName = trim($data['home_team_name']);
                $awayTeamId = (int) $data['away_team_id'];
                $awayTeamName = trim($data['away_team_name']);
                $goalsHome = (int) $data['goals_home'];
                $goalsAway = (int) $data['goals_away'];
                $goalsHomeHt = (int) $data['goals_home_ht'];
                $goalsAwayHt = (int) $data['goals_away_ht'];

                $game = $this->entityManager->getRepository(Game::class)->findOneBy(['fixtureId' => $fixtureId]);
                if (!$game) {
                    $game = new Game(
                        $fixtureId,
                        $countryName,
                        $leagueId,
                        $leagueName,
                        $seasonYear,
                        $seasonStage,
                        $date,
                        $homeTeamId,
                        $homeTeamName,
                        $awayTeamId,
                        $awayTeamName,
                        $goalsHome,
                        $goalsAway,
                        $goalsHomeHt,
                        $goalsAwayHt
                    );

                    if (!empty($data['round'])) {
                        $game->setRound(trim($data['round']));
                    }
                    if (!empty($data['referee'])) {
                        $game->setReferee(trim($data['referee']));
                    }
                    if (!empty($data['home_team_coach'])) {
                        $game->setHomeTeamCoach(trim($data['home_team_coach']));
                    }
                    if (!empty($data['away_team_coach'])) {
                        $game->setAwayTeamCoach(trim($data['away_team_coach']));
                    }

                    $this->entityManager->persist($game);
                    $imported++;

                    if ($imported % 100 === 0) {
                        $this->entityManager->flush();
                        $this->entityManager->clear();
                        $io->note("Imported $imported records so far...");
                    }
                }
            } catch (\Exception $e) {
                $io->error("Error importing row $rowIndex (fixture_id: " . ($data['fixture_id'] ?? 'unknown') . "): " . $e->getMessage());

                if (!$this->entityManager->isOpen()) {
                    $io->warning("EntityManager was closed and has been reopened.");
                }
            }

            $rowIndex++;
        }

        $this->entityManager->flush();
        $this->entityManager->clear();

        $io->success("Imported $imported football matches successfully!");
        return Command::SUCCESS;
    }
}