<?php

declare(strict_types=1);

namespace DPR\API\Infrastructure\Persistence\DAO\FileFinder;

use DPR\API\Infrastructure\Persistence\DAO\DAO;

use DPR\API\Domain\Models\BusinessUnit;

class BusinessUnitDAO extends DAO
{

    const DATABASE_NAME = 'ncparks';

    protected $BaseQuery = "SELECT * FROM " . BusinessUnitDAO::DATABASE_NAME . ".business_unit ";
    protected $InsertQuery = "INSERT INTO ncparks.business_unit (bun_title) VALUES (:title)";

    /**
     * Gets all business units
     * @return BusinessUnit[] - array of BusinessUnit objects
     */
    function getBusinessUnits($data)
    {
        $DBConn = $this->DBPool->request();
        $allBusUnits = [];

        if (key_exists("active", $data)) {
            $inactiveBusUnits = [];
            if (in_array("0", $data["active"])) {
                // Retrieve inactive business units
                $retrieveInactiveBusUnitsQuery = $this->BaseQuery . " WHERE bun_active = :inactive_bus_units";
                $valueForInactiveBusUnitsQuery = ["inactive_bus_units" => 0];
                $DBConn->queryWithNamedData($retrieveInactiveBusUnitsQuery, $valueForInactiveBusUnitsQuery);
                while ($DBConn->nextRow()) {
                    $inactiveBusUnits[] = new BusinessUnit($DBConn->getRow());
                }
                $allBusUnits["inactive"] = $inactiveBusUnits;
            }
            $activeBusUnits = [];
            if (in_array("1", $data["active"])) {
                // Retrieve active business units
                $retrieveActiveBusUnitsQuery = $this->BaseQuery . " WHERE bun_active = :active_bus_units";
                $valueForActiveBusUnitsQuery = ["active_bus_units" => 1];
                $DBConn->queryWithNamedData($retrieveActiveBusUnitsQuery, $valueForActiveBusUnitsQuery);

                while ($DBConn->nextRow()) {
                    $activeBusUnits[] = new BusinessUnit($DBConn->getRow());
                }
                $allBusUnits["active"] = $activeBusUnits;
            }
        } else {
            $DBConn->query($this->BaseQuery);
            while ($DBConn->nextRow()) {
                $allBusUnits[] = new BusinessUnit($DBConn->getRow());
            }
        }
        $this->DBPool->release($DBConn);
        return $allBusUnits;
    }

    /** Gets the business unit with the given id
     * @param int $id for the business unit
     * @return BusinessUnit|null
     */
    function getById($id)
    {
        $DBConn = $this->DBPool->request();
        $data = [
            "id" => $id
        ];
        $DBConn->queryWithNamedData(
            $this->BaseQuery . " WHERE bun_id = :id",
            $data
        );
        $BusinessUnit = null;
        if ($DBConn->nextRow()) {
            $BusinessUnit = new BusinessUnit($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $BusinessUnit;
    }

    /**
     * Gets all business units of the given status
     * @param int $active denotes the usage status of the business unit entry
     * @return BusinessUnit[] - array of BusinessUnit objects
     */
    function getByStatus($active)
    {
        $DBConn = $this->DBPool->request();
        $data = [
            "active" => $active
        ];
        $DBConn->queryWithNamedData(
            $this->BaseQuery . " WHERE bun_active = :active",
            $data
        );
        $BusinessUnits = [];
        while ($DBConn->nextRow()) {
            $BusinessUnits[] = new BusinessUnit($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $BusinessUnits;
    }

    /**
     * Increments by 1 the usage count for the specified (by id) business unit
     * @param int $id for the business unit
     * @return BusinessUnit that was updated
     */
    function incrementCountForId($id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData(
            "UPDATE ncparks.business_unit SET bun_count = bun_count + 1 WHERE bun_id = :id",
            array("id" => $id)
        );
        # fetch updated
        $DBConn->queryWithNamedData(
            $this->BaseQuery . " WHERE bun_id = :id",
            array("id" => $id)
        );
        $BusinessUnit = null;
        if ($DBConn->nextRow()) {
            $BusinessUnit = new BusinessUnit($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $BusinessUnit;
    }

    /**
     * Switches the active status for the specified (by id) business unit
     * @param int $id for the business unit
     * @return BusinessUnit that was updated
     */
    function switchActiveStatusForId($id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData(
            "UPDATE ncparks.business_unit SET bun_active = 1 - bun_active WHERE bun_id = :id",
            array("id" => $id)
        );
        # fetch updated
        $DBConn->queryWithNamedData(
            $this->BaseQuery . " WHERE bun_id = :id",
            array("id" => $id)
        );
        $BusinessUnit = null;
        if ($DBConn->nextRow()) {
            $BusinessUnit = new BusinessUnit($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $BusinessUnit;
    }

    /**
     * Adds a new entry in the business_unit table
     * @param string $title is the title for the new business unit
     * @return int id for the new business unit inserted in the DB
     */
    function addNewBusinessUnit($title)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->queryWithNamedData(
            $this->InsertQuery,
            array("title" => $title)
        );
        $newBusinessUnitId = (int) $DBConn->lastInsertID();
        $this->DBPool->release($DBConn);
        return $newBusinessUnitId;
    }
}
