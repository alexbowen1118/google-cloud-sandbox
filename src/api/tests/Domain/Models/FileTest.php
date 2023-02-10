<?php

declare(strict_types=1);

namespace Tests\Domain\Models;

use Tests\TestCase;
use DPR\API\Domain\Models\File;
use Illuminate\Support\Arr;

class FileTest extends TestCase
{
    public function fileProvider()
    {
        $data = [
            [1, 1, 'file 1', 'test1ObjectName', date_default_timezone_get(), 1, 1, 1, false, null],
            [2, 1, 'file 2', 'test2ObjectName', date_default_timezone_get(), 1, 1, 1, false, null]
        ];
        return $data;
    }

    public function fileProvider2()
    {
        $fileData = ['fil_id' => 1, 'fil_top_id' => 2, 'fil_filename' => 'test.txt', 'fil_aws_s3_object' => 'uuid4-test.txt', 'fil_time_uploaded' => date_default_timezone_get(), 'fil_uploader_id' => 10, 'fil_dot_id' => 3, 'fil_bun_id' => 5, 'fil_archived' => 1, 'fil_time_archived' => null];
        $randomNumber = random_int(0, 5);
        $randomIds = [];
        for ($i = 0; $i < $randomNumber; $i++) {
            $randomIds[] = $i;
        }
        return array(
            array(
                array(
                    "fileData" => $fileData,
                    "attributeIds" => $randomIds
                )
            )
        );
    }

    /**
     * @dataProvider fileProvider
     * @param    $data
     */
    public function testGetters($fileId, $topicId, $filename, $awsS3ObjectName, $timeUploaded, $uploaderId, $documentTypeId, $businessUnitId, $archived, $timeArchived)
    {
        $fileFromDatabase = new File(['fil_id' => $fileId, 'fil_top_id' => $topicId, 'fil_filename' => $filename, 'fil_aws_s3_object' => $awsS3ObjectName, 'fil_time_uploaded' => $timeUploaded, 'fil_uploader_id' => $uploaderId, 'fil_dot_id' => $documentTypeId, 'fil_bun_id' => $businessUnitId, 'fil_archived' => $archived, 'fil_time_archived' => $timeArchived]);
        $fileFromFrontend = new File(json_encode(['id' => $fileId, 'topicId' => $topicId, 'filename' => $filename, 'awsS3ObjectName' => $awsS3ObjectName, 'timeUploaded' => $timeUploaded, 'uploaderId' => $uploaderId, 'documentTypeId' => $documentTypeId, 'businessUnitId' => $businessUnitId, 'archived' => $archived, 'timeArchived' => $timeArchived]));

        $this->assertEquals($fileId, $fileFromDatabase->getId());
        $this->assertEquals($topicId, $fileFromDatabase->getTopicId());
        $this->assertEquals($filename, $fileFromDatabase->getFilename());
        $this->assertEquals($awsS3ObjectName, $fileFromDatabase->getAwsS3ObjectName());
        $this->assertEquals($timeUploaded, $fileFromDatabase->getTimeUploaded());
        $this->assertEquals($uploaderId, $fileFromDatabase->getUploaderId());
        $this->assertEquals($documentTypeId, $fileFromDatabase->getDocumentTypeId());
        $this->assertEquals($businessUnitId, $fileFromDatabase->getBusinessUnitId());
        $this->assertEquals($archived, $fileFromDatabase->getArchived());
        $this->assertEquals($timeArchived, $fileFromDatabase->getTimeArchived());

        $this->assertEquals($fileId, $fileFromFrontend->getId());
        $this->assertEquals($topicId, $fileFromFrontend->getTopicId());
        $this->assertEquals($filename, $fileFromFrontend->getFilename());
        $this->assertEquals($awsS3ObjectName, $fileFromFrontend->getAwsS3ObjectName());
        $this->assertEquals($timeUploaded, $fileFromFrontend->getTimeUploaded());
        $this->assertEquals($uploaderId, $fileFromFrontend->getUploaderId());
        $this->assertEquals($documentTypeId, $fileFromFrontend->getDocumentTypeId());
        $this->assertEquals($businessUnitId, $fileFromFrontend->getBusinessUnitId());
        $this->assertEquals($archived, $fileFromFrontend->getArchived());
        $this->assertEquals($timeArchived, $fileFromFrontend->getTimeArchived());
    }

    /**
     * @dataProvider fileProvider2
     * @param    $data
     */
    public function testTagAddition($data)
    {
        $file = new File($data["fileData"]);
        $file->setTags($data["attributeIds"]);
        $this->assertEquals($data["attributeIds"], $file->getTags());
    }

    /**
     * @dataProvider fileProvider2
     * @param    $data
     */
    public function testParkAddition($data)
    {
        $file = new File($data["fileData"]);
        $file->setParks($data["attributeIds"]);
        $this->assertEquals($data["attributeIds"], $file->getParks());
    }
}
