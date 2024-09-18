<?php

// src/Command/ImportExcelCommand.php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportExcelCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:import-excel')
            ->setDescription('Imports data from an Excel file into the database')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to the Excel file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputFileName = $input->getArgument('file');

        // Load and parse the Excel file
        $spreadsheet = IOFactory::load($inputFileName);

        // Iterate over the worksheets
        foreach ($spreadsheet->getAllSheets() as $worksheet) {
            $sheetTitle = $worksheet->getTitle();
            $rows = $worksheet->toArray();

            // Process the rows based on the worksheet title
            switch ($sheetTitle) {
                case 'DMHH':
                    dd($rows);
                    break;
                case 'Nhap CP':
                    dd($rows);
                    break;
                case 'Xuat CP':
                    dd($rows);
                    break;
                case 'NXT CP':
                    dd($rows);
                    break;
                // Add more cases for additional sheets if needed
            }
        }

        $this->entityManager->flush();

        $output->writeln('Data imported successfully!');

        return 0;
    }
}
