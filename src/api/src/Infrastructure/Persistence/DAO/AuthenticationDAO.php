<?php
declare(strict_types=1);
namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Domain\Models;

class AuthenticationDAO extends DAO {

    private const DB_NAME = 'ncparks';
    private const USER_TABLE = 'user';
    private const APP_TABLE = 'application';
    private const USER_APP_TABLE = 'application_user_role';
    private const ROLE_TABLE = 'role';
    private const PARK_TABLE = 'park';
    
    # Hard-coded for the visitation application
    private const VISITATION_APP_ID = 2;
    
    protected $BaseQuery = "SELECT * FROM " . self::DB_NAME;

    function getHash($username) {
        $DBConn = $this->DBPool->request();
        $hash = null;
        // Query user table
        $DBConn->query($this->BaseQuery . "." . self::USER_TABLE . " WHERE usr_status = 1 AND usr_username = ?",
            array(
                array("value" => $username, "type" => \PDO::PARAM_STR)
        ));
        if($DBConn->nextRow()) {
            $hash = ($DBConn->getRow())['usr_password_hash'];
        }
        $this->DBPool->release($DBConn);
        return $hash;
    }

    function getUserPark($username) {
        $DBConn = $this->DBPool->request();
        $Park = null;
        // Query user table to find user's associated park from unique username
        $DBConn->query($this->BaseQuery . "." . self::USER_TABLE . " WHERE usr_status = 1 AND usr_username = ?",
            array(
                array("value" => $username, "type" => \PDO::PARAM_STR)
            ));
        if($DBConn->nextRow()) {
            $parkId = ($DBConn->getRow())['usr_par_id'];
            // Query park table to get full park information
            $DBConn->query($this->BaseQuery . "." . self::PARK_TABLE  . " WHERE par_id = ?",
                array(
                    array("value" => $parkId, "type" => \PDO::PARAM_INT)
                ));
            // Create park model
            if($DBConn->nextRow()) {
                $Park = new Models\Park($DBConn->getRow());
            }
        }
        $this->DBPool->release($DBConn);
        return $Park;
    }

    function getUserAppRoleId($username) {
        $DBConn = $this->DBPool->request();
        $roleId = null;
        // Query user table
        $DBConn->query($this->BaseQuery . "." . self::USER_TABLE . " WHERE usr_status = 1 AND usr_username = ?",
            array(
                array("value" => $username, "type" => \PDO::PARAM_STR)
            ));
        if($DBConn->nextRow()) {
            $userId = ($DBConn->getRow())['usr_id'];
            // Query user app table
            $DBConn->query($this->BaseQuery . "." . self::USER_APP_TABLE  . " WHERE aur_usr_id = ? AND aur_app_id = " . self::VISITATION_APP_ID,
                array(
                    array("value" => $userId, "type" => \PDO::PARAM_INT)
                ));
            if($DBConn->nextRow()) {
                $roleId = ($DBConn->getRow())['aur_rol_id'];
            }
        }
        $this->DBPool->release($DBConn);
        return $roleId;
    }

    function getUserAppRole($username) {
        // Get role id
        $userAppRoleId = $this->getUserAppRoleId($username);
        // Find and return user app role name from it's id in role table
        $DBConn = $this->DBPool->request();
        $userAppRole = null;
        $DBConn->query($this->BaseQuery . "." . self::ROLE_TABLE . " WHERE rol_id = ?",
        array(
            array("value" => $userAppRoleId, "type" => \PDO::PARAM_INT)
        ));
        if($DBConn->nextRow()) {
            $userAppRole = ($DBConn->getRow())['rol_name'];
        }
        $this->DBPool->release($DBConn);
        return $userAppRole;
        
    }

}