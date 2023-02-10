<?php

declare(strict_types=1);

namespace DPR\API\Domain;

use Aws\S3\S3Client;

class AWSAPI
{
    private $s3Client = null;
    private $settings = null;

    public function __construct($awsSettings)
    {
        $this->settings = $awsSettings;
        $this->s3Client = S3Client::factory(array(
            "profile" => $awsSettings["credentials_profile"],
            "region" => $awsSettings["s3_bucket_region"],
            "version" => $awsSettings["s3_api_version"]
        ));
    }

    /**
     * Upload object to the AWS S3 bucket
     * @param string objectName is the Key for the S3 object upload
     * @param string content is the file content
     * @return \Aws\Result AWS Result object for the put object request
     */
    public function putObject($objectName, $content)
    {
        $result = $this->s3Client->putObject(array(
            "Bucket" => $this->settings["s3_bucket"],
            "Key"    => $objectName,
            "Body"   => $content
        ));
        return $result;
    }

    /**
     * Generate and return an AWS S3 pre-signed url for the given object name
     * @param string $objectName is the name of the object for which a presigned url is to be generated
     * @return string Presigned url for the given object
     */
    public function getPresignedUrl($objectName)
    {
        $getCommand = $this->s3Client->getCommand("GetObject", array(
            "Bucket" => $this->settings["s3_bucket"],
            "Key" => $objectName
        ));
        $request = $this->s3Client->createPresignedRequest($getCommand, "+2 minutes");
        $presignedUrl = (string) $request->getUri();
        return $presignedUrl;
    }

    /**
     * Generate and return an AWS S3 pre-signed urls for the given object names
     * @param string[] $objectNameArray is the array of object names for which a presigned urls are to be generated
     * @return string[] Presigned urls for the given object names
     */
    public function getPresignedUrls($objectNameArray)
    {
        $result = [];
        foreach ($objectNameArray as $objectName) {
            $result[] = $this->getPresignedUrl($objectName);
        }
        return $result;
    }

    /**
     * Delete an object from the AWS S3 bucket
     * @param string objectName is the name of the object to be deleted
     * @return \Aws\Result AWS Result object for the delete object request 
     */
    public function deleteObject($objectName)
    {
        $result = $this->s3Client->deleteObject(array(
            "Bucket" => $this->settings["s3_bucket"],
            "Key" => $objectName
        ));
        return $result;
    }

    /**
     * Delete an object from the AWS S3 bucket
     * @param string objectName is the name of the object to be deleted
     * @return \Aws\Result AWS Result object for the delete object version request 
     */
    public function deleteObjectVersion($objectName, $versionId)
    {
        $result = $this->s3Client->deleteObject(array(
            "Bucket" => $this->settings["s3_bucket"],
            "Key" => $objectName,
            "VersionId" => $versionId
        ));
        return $result;
    }

    /**
     * Delete multiple objects from the AWS S3 bucket
     * @param array objectsNameArray is a string array containing the names of the objects to be deleted
     * @return \Aws\Result AWS Result object for the delete object requests
     */
    public function deleteObjects($objectsNameArray)
    {
        $toDelete = [];
        foreach ($objectsNameArray as $objectName) {
            $toDelete[] = array("Key" => $objectName);
        }
        $result = $this->s3Client->deleteObjects(array(
            "Bucket" => $this->settings["s3_bucket"],
            'Delete' => array(
                'Objects' => $toDelete
            )
        ));
        return $result;
    }
}
