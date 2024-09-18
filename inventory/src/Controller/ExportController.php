<?php

namespace App\Controller;

use App\Service\TransactionService;
use App\Entity\InventoryTransaction;
use App\Service\ExternalService\S3Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\ExternalService\ExcelExportService;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExportController extends AbstractController
{
    private $excelExportService;
    private $s3Service;

    public function __construct(ExcelExportService $excelExportService, S3Service $s3Service)
    {
        $this->excelExportService = $excelExportService;
        $this->s3Service = $s3Service;
    }

    #[Route('/api/export/{filename}', name: 'get_export', methods: ['GET'])]
    public function exportAction(string $filename): Response
    {
         // Download file from S3
         $fileContent = $this->s3Service->downloadFileFromS3('uploads/' . $filename);

         if ($fileContent === null) {
             return new Response('File not found or error during download.', 404);
         }
 
         // Create a response to send the file content
         $response = new Response($fileContent);
         $response->headers->set('Content-Type', 'application/octet-stream');
         $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
 
         return $response;
    }

    #[Route('/api/small-export/{filename}', name: 'get_export', methods: ['POST'])]
    public function smallExport(string $filename, Request $request): Response
    {
        $content = $request->getContent();
        $postData = json_decode($content, true);
        $this->excelExportService->export($postData['data'],['ID','Tên SP','Mã Chính ','Mã Phụ','Khách Hàng', 'SL', 'Loại GD', 'Ngày'],$filename);
        return $this->file($filename, $filename, ResponseHeaderBag::DISPOSITION_ATTACHMENT);

    }
    
}