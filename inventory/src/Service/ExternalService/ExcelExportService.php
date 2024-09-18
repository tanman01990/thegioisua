<?php

namespace App\Service\ExternalService;




use Symfony\Component\HttpFoundation\StreamedResponse;
use Rap2hpoutre\FastExcel\FastExcel;
class ExcelExportService
{
    public function export(array $data, array $headers, string $filePath)
    {
        // $spreadsheet = new Spreadsheet();
        // $sheet = $spreadsheet->getActiveSheet();

        // $sheet->fromArray($headers, null, 'A1');

        // // Set rows
        // $rowNumber = 2;
        // foreach ($data as $row) {
        //     $sheet->fromArray(array_values($row), null, 'A' . $rowNumber);
        //     $rowNumber++;
        // }

        // $writer = new Xlsx($spreadsheet);
        // $writer->save($filePath);
        $list = collect($data);
        (new FastExcel($list))->export($filePath);
        return $filePath;
    }
}
