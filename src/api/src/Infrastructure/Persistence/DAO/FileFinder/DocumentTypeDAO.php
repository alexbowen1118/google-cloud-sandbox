<?php

declare(strict_types=1);

namespace DPR\API\Infrastructure\Persistence\DAO\FileFinder;

use DPR\API\Infrastructure\Persistence\DAO\DAO;

use DPR\API\Domain\Models\DocumentType;

class DocumentTypeDAO extends DAO
{

    protected $BaseQuery = "SELECT * FROM ncparks.document_type ";
    protected $InsertQuery = "INSERT INTO ncparks.document_type (dot_title) VALUES (:title)";

    /**
     * Gets all document types
     * @param bool - Active status of document types
     * @return DocumentType[] - array of DocumentType objects
     */
    function getDocumentTypes($data)
    {
        $DBConn = $this->DBPool->request();
        $allDocTypes = [];
        if (key_exists("active", $data)) {
            $inactiveDocTypes = [];
            if (in_array("0", $data["active"])) {
                // Retrieve inactive document types
                $retrieveInactiveDocTypesQuery = $this->BaseQuery . " WHERE dot_active = :inactive_doc_types";
                $valueForInactiveDocTypesQuery = ["inactive_doc_types" => 0];
                $DBConn->queryWithNamedData($retrieveInactiveDocTypesQuery, $valueForInactiveDocTypesQuery);
                while ($DBConn->nextRow()) {
                    $inactiveDocTypes[] = new DocumentType($DBConn->getRow());
                }
                $allDocTypes["inactive"] = $inactiveDocTypes;
            }
            $activeDocTypes = [];
            if (in_array("1", $data["active"])) {
                // Retrieve active document types
                $retrieveActiveDocTypesQuery = $this->BaseQuery . " WHERE dot_active = :active_doc_types";
                $valueForActiveDocTypesQuery = ["active_doc_types" => 1];
                $DBConn->queryWithNamedData($retrieveActiveDocTypesQuery, $valueForActiveDocTypesQuery);

                while ($DBConn->nextRow()) {
                    $activeDocTypes[] = new DocumentType($DBConn->getRow());
                }
                $allDocTypes["active"] = $activeDocTypes;
            }
        } else {
            $DBConn->query($this->BaseQuery);
            while ($DBConn->nextRow()) {
                $allDocTypes[] = new DocumentType($DBConn->getRow());
            }
        }
        $this->DBPool->release($DBConn);
        return $allDocTypes;
    }

    /** Gets the document type with the given id
     * @param int $id for the document type
     * @return DocumentType|null
     */
    function getById($id)
    {
        $DBConn = $this->DBPool->request();
        $data = [
            "id" => $id
        ];
        $DBConn->queryWithNamedData(
            $this->BaseQuery . " WHERE dot_id = :id",
            $data
        );
        $DocumentType = null;
        if ($DBConn->nextRow()) {
            $DocumentType = new DocumentType($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $DocumentType;
    }

    /**
     * Gets all document types with the given status
     * @param int $active denotes the usage status of the document type
     * @return DocumentType[]
     */
    function getByStatus($active)
    {
        $DBConn = $this->DBPool->request();
        $data = [
            "active" => $active
        ];
        $DBConn->queryWithNamedData(
            $this->BaseQuery . " WHERE dot_active = :active",
            $data
        );
        $DocumentTypes = [];
        while ($DBConn->nextRow()) {
            $DocumentTypes[] = new DocumentType($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $DocumentTypes;
    }

    /**
     * Switches the active status for the specified (by id) document type
     * @param int $id for the document type
     * @return DocumentType that was updated
     */
    function switchActiveStatusForId($id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData(
            "UPDATE ncparks.document_type SET dot_active = 1 - dot_active WHERE dot_id = :id",
            array("id" => $id)
        );
        # fetch updated
        $DBConn->queryWithNamedData(
            $this->BaseQuery . " WHERE dot_id = :id",
            array("id" => $id)
        );
        $DocumentType = null;
        if ($DBConn->nextRow()) {
            $DocumentType = new DocumentType($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $DocumentType;
    }

    /**
     * Adds a new entry in the document_type table
     * @param string $title for the new document type
     * @return int id for the document type added to the DB
     */
    function addNewDocumentType($title)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData(
            $this->InsertQuery,
            array("title" => $title)
        );
        $newDocumentTypeId = $DBConn->lastInsertID();
        $this->DBPool->release($DBConn);
        return $newDocumentTypeId;
    }
}
