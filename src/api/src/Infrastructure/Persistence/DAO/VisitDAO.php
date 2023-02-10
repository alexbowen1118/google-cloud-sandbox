<?php
declare(strict_types=1);
namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Domain\Models;

class VisitDAO extends DAO {

    protected $BaseQuery = "SELECT * FROM ncparks.visit JOIN ncparks.park ON vis_par_id=par_id JOIN ncparks.device ON vis_dev_id=dev_id ";



    function createVisit(Models\Visit $Visit) {
        $DBConn = $this->DBPool->request();
        $DBConn->query("INSERT INTO ncparks.visit (vis_id, vis_par_id, vis_dev_id, vis_timestamp, vis_count, vis_count_calculated, vis_comments, vis_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            array(
                array("value" => $Visit->id, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->par_id, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->dev_id, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->timestamp, "type" => \PDO::PARAM_STR),
                array("value" => $Visit->count, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->count_calculated, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->comments, "type" => \PDO::PARAM_STR),
                array("value" => $Visit->status, "type" => \PDO::PARAM_INT)
            ));
        $id = $DBConn->insertID();
        $this->DBPool->release($DBConn);
        return $this->getVisitById($id);
    }



    function getVisitById($id) {
        $DBConn = $this->DBPool->request();
        $DBConn->query($this->BaseQuery." WHERE vis_status = 1 AND vis_id = ?",
            array(
                array("value" => $id, "type" => \PDO::PARAM_INT)
            ));
        $Visit = null;
        if($DBConn->nextRow()) {
            $Visit = new Models\Visit($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $Visit;
    }



    function getAllVisits() {
        $DBConn = $this->DBPool->request();
        $DBConn->query($this->BaseQuery." WHERE vis_status = 1");
        $Visits = [];
        while($DBConn->nextRow()) {
            $Visits[] = new Models\Visit($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $Visits;
    }



    function getVisitsByPark($par_id) {
        $DBConn = $this->DBPool->request();
        $DBConn->query($this->BaseQuery." WHERE vis_status = 1 AND vis_par_id = ?",
            array(
                array("value" => $par_id, "type" => \PDO::PARAM_INT)
            ));
        $Visits = [];
        while($DBConn->nextRow()) {
            $Visits[] = new Models\Visit($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $Visits;
    }



    function getVisitsByDevice($dev_id) {
        $DBConn = $this->DBPool->request();
        $DBConn->query($this->BaseQuery." WHERE vis_status = 1 AND vis_dev_id = ?",
            array(
                array("value" => $dev_id, "type" => \PDO::PARAM_INT)
            ));
        $Visits = [];
        while($DBConn->nextRow()) {
            $Visits[] = new Models\Visit($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $Visits;
    }



    function getDayVisitsByPark($par_id)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->query("SELECT vis_id, vis_par_id, vis_dev_id, DATE_FORMAT(vis_timestamp, '%Y-%m-%d 00:00:00') as vis_timestamp, SUM(vis_count) as vis_count, 
    SUM(vis_count_calculated) as vis_count_calculated, vis_comments, vis_status FROM ncparks.visit 
    JOIN ncparks.park ON vis_par_id=par_id JOIN ncparks.device ON vis_dev_id=dev_id 
    WHERE vis_status = 1 AND vis_par_id = ? GROUP BY year(vis_timestamp), month(vis_timestamp), day(vis_timestamp)",
            array(
                array("value" => $par_id, "type" => \PDO::PARAM_INT)
            ));
        $Visits = [];
        while($DBConn->nextRow()) {
            $Visits[] = new Models\Visit($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $Visits;
    }



    function getMonthVisits()
    {
        $DBConn = $this->DBPool->request();
        $DBConn->query("SELECT vis_id, vis_par_id, vis_dev_id, DATE_FORMAT(vis_timestamp, '%Y-%m-01 00:00:00') as vis_timestamp,
    SUM(vis_count) as vis_count, SUM(vis_count_calculated) as vis_count_calculated, vis_comments, vis_status FROM ncparks.visit 
    JOIN ncparks.park ON vis_par_id=par_id JOIN ncparks.device ON vis_dev_id=dev_id 
    WHERE vis_status = 1 GROUP BY year(vis_timestamp), month(vis_timestamp)");
        $Visits = [];
        while($DBConn->nextRow()) {
            $Visits[] = new Models\Visit($DBConn->getRow());
        }
        $this->DBPool->release($DBConn);
        return $Visits;
    }


    function updateVisit(Models\Visit $Visit) {
        $DBConn = $this->DBPool->request();
        $DBConn->query("UPDATE ncparks.visit SET vis_par_id = ?, vis_dev_id = ?, vis_timestamp = ?, vis_count = ?, vis_count_calculated = ?, vis_comments = ?, vis_status = ? WHERE vis_id = ?",
            array(
                array("value" => $Visit->par_id, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->dev_id, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->timestamp, "type" => \PDO::PARAM_STR),
                array("value" => $Visit->count, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->count_calculated, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->comments, "type" => \PDO::PARAM_STR),
                array("value" => $Visit->status, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->id, "type" => \PDO::PARAM_INT)
            ));
        $this->DBPool->release($DBConn);
        return $this->getVisitById($Visit->id);
    }



    function deleteVisit($id) {
        $Visit = $this->getVisitById($id);
        $Visit->setStatus(0);
        $DBConn = $this->DBPool->request();
        $DBConn->query("UPDATE ncparks.visit SET vis_par_id = ?, vis_dev_id = ?, vis_timestamp = ?, vis_count = ?, vis_count_calculated = ?, vis_comments = ?, vis_status = ? WHERE vis_id = ?",
            array(
                array("value" => $Visit->par_id, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->dev_id, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->timestamp, "type" => \PDO::PARAM_STR),
                array("value" => $Visit->count, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->count_calculated, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->comments, "type" => \PDO::PARAM_STR),
                array("value" => $Visit->status, "type" => \PDO::PARAM_INT),
                array("value" => $Visit->id, "type" => \PDO::PARAM_INT)
            ));
        $this->DBPool->release($DBConn);
        return $this->getVisitById($Visit->id);
    }

}