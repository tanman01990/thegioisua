<?php

namespace App\Service\ExternalService;



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExportService
{
    public function export(array $data, array $headers, string $filePath)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($headers, null, 'A1');

        // Set rows
        $rowNumber = 2;
        foreach ($data as $row) {
            $sheet->fromArray(array_values($row), null, 'A' . $rowNumber);
            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $filePath;
    }
}
