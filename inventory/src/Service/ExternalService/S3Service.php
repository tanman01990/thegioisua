<?php

namespace App\Service\ExternalService;

// src/Service/S3Service.php


use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class S3Service
{
    private $s3Client;
    private $bucketName;

    public function __construct()
    {
        // Initialize the S3 client
        $this->s3Client = new S3Client([
            'region' => $_ENV['AWS_REGION'],
            'version' => 'latest',
            'credentials' => [
                'key' => $_ENV['AWS_ACCESS_KEY_ID'],
                'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
            ],
        ]);

        $this->bucketName = $_ENV['AWS_BUCKET_NAME'];
    }

    public function uploadFile(string $filePath , $key)
    {
        try {
            // Upload file to S3
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucketName,
                'Key' => $key,
                'SourceFile' => $filePath,
            ]);

            // Return the URL of the uploaded file
            return $result['ObjectURL'];
        } catch (AwsException $e) {
            // Handle any errors
            return false;
        }
    }

    public function listFilesInFolder(string $folder): ?array
    {
        try {
            // List objects in the specified folder (prefix)
            $result = $this->s3Client->listObjectsV2([
                'Bucket' => $this->bucketName,
                'Prefix' => rtrim($folder, '/') . '/', // Make sure folder ends with '/'
            ]);

            // Check if the result contains files (Contents)
            if (isset($result['Contents'])) {
                $files = [];
                foreach ($result['Contents'] as $object) {
                    $files[] = $object['Key']; // Get the file key (path in S3)
                }
                return $files;
            } else {
                return [];
            }
        } catch (AwsException $e) {
            // Handle any errors from AWS
            return null;
        }
    }

    public function downloadFileFromS3(string $key): ?string
    {
        try {
            // Download the object from S3
            $result = $this->s3Client->getObject([
                'Bucket' => $this->bucketName,
                'Key'    => $key,  // The file key (path in the S3 bucket)
            ]);

            // Return the file content
            return $result['Body'];
        } catch (AwsException $e) {
            // Handle any errors
            return null;
        }
    }
}

