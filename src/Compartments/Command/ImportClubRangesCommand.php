<?php

namespace App\Compartments\Command;

use App\Compartments\Entity\ClubRange;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-club-ranges',
    description: 'Imports club range data from league sheets in an XLSX file into the database'
)]
class ImportClubRangesCommand extends Command
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
            ->addArgument('file', InputArgument::REQUIRED, 'Name of the XLSX file in the public directory (e.g., club_ranges.xlsx)');
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
        $imported = 0;

        $leagueSheets = [
            'Anglia', 'Francja', 'Włochy', 'Hiszpania', 'Niemcy1', 'Niemcy2', 'Holandia', 'Szwajcaria', 'Portugalia',
            'Belgia', 'Holandia2', 'Anglia2', 'Polska', 'Polska2', 'Czechy', 'Słowenia', 'Dania', 'Chorwacja',
            'Szkocja', 'Meksyk', 'Rumunia', 'Serbia', 'Turcja', 'Włochy2', 'Austria', 'Norwegia', 'Szwecja', 'Inne'
        ];

        foreach ($leagueSheets as $sheetName) {
            if (!$spreadsheet->sheetNameExists($sheetName)) {
                $io->warning("Sheet '$sheetName' not found, skipping...");
                continue;
            }

            $worksheet = $spreadsheet->getSheetByName($sheetName);
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            $header = $worksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, false)[0];
            $header = array_map('trim', $header);
            $io->note("Headers for sheet '$sheetName': " . implode(', ', $header));

            $rowIndex = 2;
            while ($rowIndex <= $highestRow) {
                try {
                    $row = $worksheet->rangeToArray('A' . $rowIndex . ':' . $highestColumn . $rowIndex, null, true, false)[0];
                    $io->note("Row $rowIndex in sheet '$sheetName': " . implode(', ', array_map(fn($v) => $v ?? 'null', $row)));

                    $clubName = trim($row[0] ?? '');
                    if (empty($clubName) || str_starts_with($clubName, 'Unnamed:')) {
                        $rowIndex++;
                        continue;
                    }

                    $series = [];
                    for ($i = 1; $i <= 38; $i++) {
                        $value = isset($row[$i]) ? trim($row[$i]) : null;
                        $series[$i] = $value === 'tak' ? true : ($value === 'nie' ? false : null);
                    }

                    $clubRange = $this->entityManager->getRepository(ClubRange::class)
                        ->findOneBy(['clubName' => $clubName, 'league' => $sheetName]);

                    if (!$clubRange) {
                        $clubRange = new ClubRange($clubName, $sheetName, $series);
                        $this->entityManager->persist($clubRange);
                        $imported++;
                        $io->note("Imported club '$clubName' in league '$sheetName'");
                    }

                    if ($imported % 100 === 0) {
                        $this->entityManager->flush();
                        $this->entityManager->clear();
                        $io->note("Imported $imported records so far...");
                    }
                } catch (\Exception $e) {
                    $io->error("Error importing row $rowIndex in sheet '$sheetName': " . $e->getMessage());
                    if (!$this->entityManager->isOpen()) {
                        $io->warning("EntityManager was closed and has been reopened.");
                    }
                }

                $rowIndex++;
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear();

        $io->success("Imported $imported club ranges successfully!");
        return Command::SUCCESS;
    }
}