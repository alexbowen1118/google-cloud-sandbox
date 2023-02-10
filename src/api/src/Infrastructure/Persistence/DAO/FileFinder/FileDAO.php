<?php

declare(strict_types=1);

namespace DPR\API\Infrastructure\Persistence\DAO\FileFinder;

use DPR\API\Infrastructure\Persistence\DAO\DAO;

use DPR\API\Domain\Models\File;

class FileDAO extends DAO
{
    protected $BaseQuery = "SELECT * FROM ncparks.file ";
    protected $GetFileS3ObjectNameQuery = "SELECT fil_aws_s3_object FROM ncparks.file WHERE fil_id = :fil_id";
    protected $UpdateBaseQuery = "UPDATE ncparks.file SET ";
    protected $FileDeleteBaseQuery = "DELETE FROM ncparks.file WHERE fil_id = :fil_id";
    protected $TagDeleteBaseQuery = "DELETE FROM ncparks.file_tag WHERE flt_fil_id = :fil_id";
    protected $ParkDeleteBaseQuery = "DELETE FROM ncparks.file_park WHERE flp_fil_id = :fil_id";

    /** 
     * Gets the file with the given id
     * @param int $id for the file
     * @return File object, or null
     */
    function getById($id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData($this->BaseQuery . " WHERE fil_id = :fil_id", ["fil_id" => $id]);
        $File = null;
        if ($DBConn->nextRow()) {
            $File = new File($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $File;
    }

    /** 
     * Gets all files
     * @return File[] - array of File objects
     */
    function getFiles()
    {
        $DBConn = $this->DBPool->request();
        $DBConn->query($this->BaseQuery);
        $files = [];
        while ($DBConn->nextRow()) {
            $files[] = new File($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $files;
    }

    /**
     * Gets all files where the topic id matches the given topic id
     * @param string top_id is the topic id
     * @return File[] - array of File objects
     */
    function getFilesByTopicId($top_id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData($this->BaseQuery . " WHERE fil_top_id = :fil_top_id", ["fil_top_id" => $top_id]);
        $files = [];
        while ($DBConn->nextRow()) {
            $files[] = new File($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $files;
    }

    /**
     * Gets all archived files where the topic id matches the given topic id
     * @param string top_id is the topic id
     * @return File[] - array of File objects
     */
    function getArchivedFilesByTopicId($top_id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData($this->BaseQuery . " WHERE fil_top_id = :fil_top_id AND fil_archived = 1", ["fil_top_id" => $top_id]);
        $files = [];
        while ($DBConn->nextRow()) {
            $files[] = new File($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $files;
    }

    /**
     * Gets all unarchived files where the topic id matches the given topic id
     * @param string top_id is the topic id
     * @return File[] - array of File objects
     */
    function getUnarchivedFilesByTopicId($top_id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData($this->BaseQuery . " WHERE fil_top_id = :fil_top_id AND fil_archived = 0", ["fil_top_id" => $top_id]);
        $files = [];
        while ($DBConn->nextRow()) {
            $files[] = new File($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $files;
    }

    /**
     * Switches the archive status for the specified (by id) file
     * @param int $id for the file
     * @return File that was updated
     */
    function switchArchiveStatusForId($id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData(
            $this->UpdateBaseQuery . "fil_archived = 1 - fil_archived WHERE fil_id = :id",
            array("id" => $id)
        );

        # fetch updated
        $DBConn->queryWithNamedData(
            $this->BaseQuery . " WHERE fil_id = :id",
            array("id" => $id)
        );

        $File = null;
        if ($DBConn->nextRow()) {
            $File = new File($DBConn->getRow());
        }

        $this->DBPool->release($DBConn);
        return $File;
    }

    /**
     * Gets the AWS S3 object name for the given file id
     * @param string id is the file id
     * @return string AWS S3 object name for the specified file
     */
    function getAWSS3ObjectName($id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData($this->GetFileS3ObjectNameQuery, array("fil_id" => $id));
        $fileAWSS3ObjectName = null;
        if ($DBConn->nextRow()) {
            $fileAWSS3ObjectName = $DBConn->getRow()["fil_aws_s3_object"];
        }
        return $fileAWSS3ObjectName;
    }

    function deleteFile($id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData($this->FileDeleteBaseQuery, array("fil_id" => $id));
        $DBConn->queryWithNamedData($this->TagDeleteBaseQuery, array("fil_id" => $id));
        $DBConn->queryWithNamedData($this->ParkDeleteBaseQuery, array("fil_id" => $id));
        $this->DBPool->release($DBConn);
    }
}
