<?php

declare(strict_types=1);

namespace DPR\API\Infrastructure\Persistence\DAO\FileFinder;

use DPR\API\Infrastructure\Persistence\DAO\DAO;
use DPR\API\Infrastructure\Persistence\DBException;

class UploadDAO extends DAO
{

    /** 
     * Takes in a request body from a POST request for file uploads and persists the associated attributes - 
     * (topic, business unit, document type, tags, parks) in the appropriate tables
     * @param mixed $data json request body for file upload POST endpoint
     */
    function uploadFiles($data)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->beginTransaction();
        ## query strings
        $topicInsertQuery = "INSERT IGNORE INTO ncparks.topic (top_title, top_description) VALUES (:top_title, :top_description)";
        $topicGetQuery = "SELECT top_id from ncparks.topic WHERE top_title = :top_title";
        $fileInsertQuery = "INSERT INTO ncparks.file (fil_top_id, fil_filename, fil_aws_s3_object, fil_time_uploaded, fil_uploader_id, fil_dot_id, fil_bun_id) "
            . "VALUES (:fil_top_id, :fil_filename, :fil_aws_s3_object, UTC_TIMESTAMP(), :fil_uploader_id, :fil_dot_id, :fil_bun_id)";
        $fileTagInsertQuery = "INSERT INTO ncparks.file_tag (flt_fil_id, flt_tag_id) VALUES (:file_id, :tag_id)";
        $fileParkInsertQuery = "INSERT INTO ncparks.file_park (flp_fil_id, flp_par_id) VALUES (:file_id, :park_id)";
        try {
            if(key_exists("replace", $data)) {
                $fileReplaceQuery = "UPDATE ncparks.file SET fil_archived = 1, fil_time_uploaded = UTC_TIMESTAMP() WHERE fil_id = ?";
                foreach($data["replace"] as $file) {
                    $DBConn->query($fileReplaceQuery, array(array("value" => $file, "type" => \PDO::PARAM_INT)));
                }
            }
            #### INSERT IGNORE INTO topic TABLE
            $DBConn->queryWithNamedData($topicInsertQuery, array("top_title" => $data["topic"]["title"], "top_description" => $data["topic"]["description"]));
            $DBConn->queryWithNamedData($topicGetQuery, array("top_title" => $data["topic"]["title"]));
            if ($DBConn->nextRow()) {
                $topicId = $DBConn->getRow()["top_id"];
            } else {
                throw new DBException("Error while fetching topic id!", $topicGetQuery);
            }
            #### INSERT INTO file table
            foreach ($data["files"] as $file) {
                $fileData = [
                    "fil_top_id" => $topicId,
                    "fil_filename" => $file["filename"],
                    "fil_aws_s3_object" => $file["aws_s3_object_name"],
                    "fil_uploader_id" => $data["uploader_id"],
                    "fil_dot_id" => $file["document_type_id"],
                    "fil_bun_id" => $file["business_unit_id"]
                ];
                $DBConn->queryWithNamedData($fileInsertQuery, $fileData);
                $fileLastInsertId = (int) $DBConn->lastInsertID();
                #### INSERT INTO file_tag table
                foreach ($file["tags"] as $item) {
                    $DBConn->queryWithNamedData($fileTagInsertQuery, array("file_id" => $fileLastInsertId, "tag_id" => $item));
                }
                #### INSERT INTO file_park table
                foreach ($file["parks"] as $item) {
                    $DBConn->queryWithNamedData($fileParkInsertQuery, array("file_id" => $fileLastInsertId, "park_id" => $item));
                }
            }
            $DBConn->commit();
        } catch (DBException $e) {
            $DBConn->rollBack();
            $this->DBPool->release($DBConn);
            throw $e;
        }
    }
}
