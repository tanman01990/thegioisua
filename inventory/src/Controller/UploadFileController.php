<?php

namespace App\Controller;

use DateTime;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use App\Common\Helper;
use App\Entity\Product;
use App\Entity\Customer;
use App\Entity\Inventory;
use Psr\Log\LoggerInterface;
use function PHPSTORM_META\type;
use App\Entity\InventoryTransaction;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ExternalService\S3Service;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UploadFileController extends AbstractController
{

    private $entityManager;
    private $logger;
    private $s3Service;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, S3Service $s3Service)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->s3Service = $s3Service;
    }
    #[Route('/api/upload', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        $this->logger->info('Start upload');
        $file = $request->files->get('file');
        $type = $request->request->get('type');
        $inventory = $request->request->get('inventory');
        $error = '';
        $month = '';

        if ($file) {
            $filename = uniqid() . '.' . $file->guessExtension();
            $file->move($this->getParameter('uploads_directory'), $filename);
            $filePath = $this->getParameter('uploads_directory') . '/' . $filename;
            // $this->processExcelFile($filePath);
            $spreadsheet = IOFactory::load($filePath);
            // get sheet by title
            $rows =  null;
            // Process the rows based on the worksheet title
            switch ($type) {
                case 'product':
                    $worksheet = $spreadsheet->getSheetByName('DMHH');
                    $rows = $worksheet->toArray();
                    foreach ($rows as $row) {
                        if ($row[0] == null) {
                            continue;
                        } else {
                            $code = $row[1];
                            // Assuming the first column is category, the second is product, and the third is customer
                            $product = $this->entityManager->getRepository(Product::class)->findOneBy(['code' => $code]);
                            if (!$product && intval($row[0]) > 0) {
                                $product = new Product();
                                $product->setCode($row[1]);
                                $product->setMainCode($row[1]);
                                $product->setName($row[2]);
                                $product->setUnit($row[3]);
                                $product->setPrice((floatval($row[4])));
                                $product->setPriceBuy((floatval($row[4])));
                                $product->setQuantity(0);
                                $this->entityManager->persist($product);
                            }
                            $this->entityManager->flush();
                        }
                    }
                    break;
                case 'import_transaction':
                    $worksheet = $spreadsheet->getSheetByName('Nhap CP');
                    if (!$worksheet) {
                        throw new Exception("Khong tồn tại dữ liệu nhập !!");
                    }

                    $rows = $worksheet->toArray();
                    $month = $this->handleImportProductIntoInventory($rows, $filename, $inventory, 'Nhap');
                    break;
                case 'export_transaction':
                    $this->logger->info('Export transaction');
                    $worksheet = $spreadsheet->getSheetByName('Xuat CP');
                    if (!$worksheet) {
                        throw new Exception("Khong tồn tại dữ liệu xuất !!");
                    }
                    $rows = $worksheet->toArray();
                    $month = $this->handleImportProductIntoInventory($rows, $filename, $inventory, 'Xuat');
                    break;
            }
            $s3Key = 'uploads/' . $month . '.xlsx';
            $result = $this->s3Service->uploadFile($filePath, $s3Key);
            return new JsonResponse(['status' => 'success', 'filename' => $filename]);
        }
        return new JsonResponse(['status' => 'error', 'message' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
    }

    public function handleImportProductIntoInventory($rows, $fileName, $inventoryId, $type = 'Xuat')
    {
        $this->logger->info('Start import');
        $inventory = $this->entityManager->getRepository(Inventory::class)->findOneBy(['id' => $inventoryId]);
        $month = '';
        $inventoryName = '';
        // try {
        foreach ($rows as $row) {

            if ($row[1] == null) {
                $this->logger->info('Not found');
                continue;
            } else {
                if ($row[3] === 'MÃ HÀNG') {
                    continue;
                }
                $isNew = false;
                $exchangedQuantity = 0;
                $transactionDate = $row[1]; // dateTransaction
                $this->logger->info($transactionDate);
                $customerCode = $row[2]; // customerCode
                $productCode = $row[3]; // productCode
                $productName = $row[4]; // productName
                $quantity = Helper::RemoveThousandsSeparator($row[5]); // quantity
                $marked = '';


                // $totalBeforeDecrease = $row[9]; // total before decrease
                // $totalAfterDecrease = $row[10]; // total after decrease
                // Assuming the first column is category, the second is product, and the third is customer
                $product = $this->entityManager->getRepository(Product::class)->findOneBy(['code' => $productCode]);
                $this->logger->info('Price:' . $row[7]);
                $this->logger->info('quantity:' . $row[5]);
                $unitPrice = Helper::CheckNumber(Helper::RemoveThousandsSeparator($row[7])); // price
                $totalPrice = Helper::CheckNumber(Helper::RemoveThousandsSeparator($row[8])); // totalPrice

                // $customer = $this->entityManager->getRepository(Customer::class)->findOneBy(['code' => $customerCode]);
                if (!$product) {
                    $product = new Product();
                    $product->setCode($productCode);
                    $product->setMainCode($productCode);
                    $this->logger->info('This is an info message::' . $productCode);
                    $product->setName($productName ?? 'NOT FOUND');
                    $product->setQuantity(intval($quantity));
                    $isNew = true;
                    $product->setQuantity($product->getQuantity() - intval($quantity));
                    $product->setPrice(floatval($unitPrice));
                    $product->setPriceBuy(floatval($unitPrice));
                    $this->entityManager->persist($product);
                }


                if ($product && (!empty($productCode) && $product->getCode() !== 'MÃ HÀNG')) {
                    if ($type == 'Xuat') {
                        $exchangedQuantity = Helper::CheckNumber($row[6]); // exchanged quantity
                        $product->setQuantity($product->getQuantity() - intval($quantity));
                        $product->setPrice(floatval($unitPrice));
                        $product->setPriceBuy(floatval($unitPrice));
                        $marked = $row[10];
                    } elseif ($type == 'Nhap') {
                        $marked = $row[6];
                        $product->setQuantity($product->getQuantity() + intval($quantity));
                        $product->setPrice(floatval($unitPrice));
                        $product->setPriceBuy(floatval($unitPrice));
                    }
                    $inventoryTransaction = new InventoryTransaction();
                    $inventoryTransaction->setProduct($product);
                    $inventoryTransaction->setProductName($product->getName());
                    $inventoryTransaction->setCustomer(null);
                    $inventoryTransaction->setTransactionType($type);
                    $inventoryTransaction->setCustomerName($customerCode);
                    $inventoryTransaction->setTotalPrice($totalPrice);
                    $date = $transactionDate ? $transactionDate : null;

                    if ($date) {
                        $date = Carbon::createFromFormat('d/m/Y', $date);
                        if (empty($month)) {
                            $month =  $date->format('Y-m');
                        }
                        $formattedDate = $date->format('Y-m-d');
                        $inventoryTransaction->setTransactionDate(new DateTime($formattedDate, new DateTimeZone("UTC")));
                    }
                    $inventoryTransaction->setIsNew($isNew);
                    $inventoryTransaction->setExchangedQuantity($exchangedQuantity);
                    $inventoryTransaction->setQuantity(intval($quantity));
                    $inventoryTransaction->setUnitPrice(floatval($unitPrice));
                    $inventoryTransaction->setPrice(floatval($unitPrice));
                    $inventoryTransaction->setTotalPrice(0);
                    $inventoryTransaction->setReference($marked ?? '');
                    $inventoryTransaction->setRemarks($marked ?? '');
                    $inventoryTransaction->setStatus(0);
                    $inventoryTransaction->setDiscount(0);
                    $inventoryTransaction->setCreatedBy('admin');
                    $inventoryTransaction->setUpdatedBy('admin');
                    $inventoryTransaction->setTotalAfterDecrease(0);
                    $inventoryTransaction->setTotalBeforeDecrease(0);
                    $inventoryTransaction->setFileName($fileName);
                    if ($inventory) {
                        $inventoryTransaction->setInventory($inventory);
                        $inventoryTransaction->setNameInventory($inventory->getName());
                        if (empty($inventoryName)) {
                            $inventoryName = $inventory->getName();
                        }
                    }

                    $this->entityManager->persist($inventoryTransaction);
                }
                $this->entityManager->flush();
            }
        }
        // } catch (\Exception $e) {
        //     // return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        // }
        return $month . "_" . $inventoryName;
    }
}
