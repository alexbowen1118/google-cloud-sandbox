<?php

declare(strict_types=1);

namespace DPR\API\Domain\Models;

use \Datetime;
use DPR\API\Domain\DomainModel;

/**
 * @todo Update class when implementing delete requests
 */
class DeleteRequest extends DomainModel
{
    public $id;
    public $fileId;
    public $requesterId;
    public $reason;
    public $requestTime;

    public function __construct($data)
    {
        $this->JSONFields = array("id", "fileId", "requesterId", "reason", "requestTime");
        if (is_array($data)) { //Coming from the database
            $this->id = $data["dlr_id"];
            $this->fileId = $data["dlr_fil_id"];
            $this->requesterId = $data["dlr_requester_id"];
            $this->reason = $data["dlr_reason"];
            $this->requestTime = $data["dlr_request_time"];
        } elseif (is_object($data)) { //Coming from JSON
            if (isset($data->id)) $this->setId($data->id);
            if (isset($data->fileId)) $this->setFileId($data->fileId);
            if (isset($data->requesterId)) $this->setRequesterId($data->requesterId);
            if (isset($data->reason)) $this->setReason($data->reason);
            if (isset($data->requestTime)) $this->setRequestTime($data->requestTime);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getFileId()
    {
        return $this->fileId;
    }

    public function setFileId($fileId)
    {
        $this->fileId = $fileId;
    }

    public function getRequesterId()
    {
        return $this->requesterId;
    }

    public function setRequesterId($requesterId)
    {
        $this->requesterId = $requesterId;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function getRequestTime()
    {
        return $this->requestTime;
    }

    public function setRequestTime($requestTime)
    {
        $this->requestTime = $requestTime;
    }
}
