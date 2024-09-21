<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Service\ExternalService\S3Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\ExternalService\ExcelExportService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExportController extends AbstractController
{
    private $excelExportService;
    private $s3Service;
    private $logger;

    public function __construct(ExcelExportService $excelExportService,LoggerInterface $logger, S3Service $s3Service)
    {
        $this->excelExportService = $excelExportService;
        $this->s3Service = $s3Service;
        $this->logger = $logger;
    }

    #[Route('/api/export', name: 'api_export', methods: ['POST'])]
    public function exportAction(Request $request): Response
    {
        $content = $request->getContent();
        $postData = json_decode($content, true);
        $filename =  $postData['filename'];
         // Download file from S3
         $fileContent = $this->s3Service->downloadFileFromS3('uploads/' . $filename);

         if ($fileContent === null) {
             return new Response('File not found or error during download.', 404);
         }
 
         // Create a response to send the file content
         $response = new Response($fileContent);
         $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
         $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
         $response->headers->set('Pragma', 'public');
         $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
         $response->headers->set('Content-Transfer-Encoding', 'binary');
 
         // Set CORS headers
         $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
         $response->headers->set('Access-Control-Allow-Methods', 'POST, OPTIONS');
         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
         $response->headers->set('Access-Control-Allow-Credentials', 'true');
         $response->headers->set('Access-Control-Expose-Headers', 'Content-Disposition, Content-Type');
         return $response;
    }

    #[Route('/api/list-file', name: 'get_list', methods: ['GET'])]
    public function listFile(): Response
    {
         // Download file from S3
         $result = $this->s3Service->listFilesInFolder('uploads');
         return new JsonResponse(['files' => $result]);
         
    }

    #[Route('/api/small-export', name: 'get_export', methods: ['POST'])]
    public function smallExport(Request $request): Response
    {
        $content = $request->getContent();
        $postData = json_decode($content, true);
        $originalArray = $postData['data'];
        $type = $postData['type'];
        $fileName = $type == 'Nhap' ? 'data-nhap.xlsx' : 'data-xuat.xlsx';
        $newKeys = ['ID','Tên SP','Mã Chính ','Mã Phụ','Khách Hàng', 'SL', 'Loại GD', 'Ngày'];
        $result = array_map(function($subArray) use ($newKeys) {
            return array_combine($newKeys, array_values($subArray));
        }, $originalArray);
        $this->excelExportService->export($result,['ID','Tên SP','Mã Chính ','Mã Phụ','Khách Hàng', 'SL', 'Loại GD', 'Ngày'],$fileName);
        $response = new StreamedResponse(function() use ($fileName) {
            $outputStream = fopen('php://output', 'wb');
            $fileStream = fopen($fileName, 'rb');
            stream_copy_to_stream($fileStream, $outputStream);
            fclose($fileStream);
            fclose($outputStream);
            unlink($fileName);  // Delete the temporary file
        });
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        // Set CORS headers
        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
        $response->headers->set('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Expose-Headers', 'Content-Disposition, Content-Type');
        return $response;

    }

    #[Route('/api/small-export', name: 'export_data_options', methods: ['OPTIONS'])]
    public function handleOptions(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
        $response->headers->set('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Max-Age', '3600');

        return $response;
    }

    #[Route('/api/export', name: 'export_data_options', methods: ['OPTIONS'])]
    public function handleExportOptions(Request $request): Response
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
        $response->headers->set('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Max-Age', '3600');

        return $response;
    }
    
}