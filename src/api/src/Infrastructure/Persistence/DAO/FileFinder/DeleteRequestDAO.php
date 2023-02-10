<?php

declare(strict_types=1);

namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Domain\Models\DeleteRequest;
use Exception;

class DeleteRequestDAO extends DAO
{

    protected $BaseQuery = "SELECT * FROM ncparks.delete_request ";
    protected $InsertQuery = "INSERT INTO ncparks.delete_request (dlr_fil_id, dlr_requester_id, dlr_reason, dlr_request_time) VALUES (:dlr_fil_id, :dlr_requester_id, :dlr_reason, UTC_TIMESTAMP())";
    protected $DeleteDeleteRequestQuery = "DELETE FROM ncparks.delete_request WHERE dlr_id = :dlr_id";
    protected $DeleteFileQuery = "DELETE FROM ncparks.file WHERE fil_id = :fil_id";

    /**
     * Gets all delete requests
     * @return DeleteRequest[] - array of DeleteRequest objects
     */
    function getDeleteRequests()
    {
        $DBConn = $this->DBPool->request();
        $DBConn->query($this->BaseQuery);
        $DeleteRequests = [];
        while ($DBConn->nextRow()) {
            $DeleteRequests[] = new DeleteRequest($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $DeleteRequests;
    }

    /** 
     * Gets the delete request with the given id
     * @param int $id for the delete request
     * @return DeleteRequest object, or null
     */
    function getById($id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData(
            $this->BaseQuery . "WHERE dlr_id = :dlr_id",
            array("dlr_id => $id")
        );
        $DeleteRequest = null;
        if ($DBConn->nextRow()) {
            $DeleteRequest = new DeleteRequest($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $DeleteRequest;
    }

    /** 
     * Create a new file delete request
     * @param mixed information for the delete request,
     * consists of the requester's user id, file id, and the reason for deletion of the file
     * @return int id for the delete request added to the DB
     */
    function createDeleteRequest($data)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData(
            $this->InsertQuery,
            array(
                "dlr_fil_id" => $data["file_id"],
                "dlr_requester_id" => $data["requester_id"],
                "dlr_reason" => $data["reason"]
            )
        );
        $newDeleteRequestId = (int) $DBConn->lastInsertID();
        $this->DBPool->release($DBConn);
        return $newDeleteRequestId;
    }

    /**
     * Actions performed after accepting a delete request
     * Deletes the file entry specified by the id, which cascades
     * and deletes associated file tags / parks / delete requests
     * @param int fil_id is the id of the file entry to be deleted
     */
    function acceptDeleteRequest($fil_id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->beginTransaction();
        try {
            $DBConn->queryWithNamedData($this->DeleteFileQuery, array("fil_id" => $fil_id));
        } catch (Exception $e) {
            $DBConn->rollBack();
            $this->DBPool->release($DBConn);
            throw $e;
        }
    }

    /**
     * Action performed if a delete request is denied
     * Delete request entry is deleted
     * @param int id for the delete request
     */
    function denyDeleteRequest($id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData($this->DeleteDeleteRequestQuery, array("dlr_id" => $id));
        $this->DBPool->release($DBConn);
    }
}
