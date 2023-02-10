<?php
declare(strict_types=1);
namespace DPR\API\Infrastructure\Persistence;

/**
 *
 * DBPDO LIBRARY FOR PHP - VERSION 1.0 - http://ignacioxd.com/
 *
 * Copyright (C) Ignacio X. Domínguez
 *
 * For more information, visit http://ignacioxd.com/
 *
 * This notice may not be removed or altered from any source distribution.
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the author be held liable for any damages arising from the
 * use of this software. Permission is granted to anyone to use this software
 * for any purpose, including commercial applications subject to the following
 * restrictions:
 *
 * 1. The origin of this software must not be misrepresented; you must not claim
 * that you wrote the original software. If you use this software in a product,
 * an acknowledgment in the product would be appreciated but is not required.
 *
 * 2. Altered source versions must be plainly marked as such, and must not be
 * misrepresented as being the original software.
 *
 * 3. This notice may not be removed or altered from any source distribution.
 *
 */

/**
 * Description of DBPDO
 *
 * @author ignacioxd
 */
class DBPDO {
	private $DBConn = null;
	private $Record = null;

	private $QueryID = null;

	private $engine;
	private $host;
	private $port;
	private $database;
	private $user;
	private $password;
	private $charset;

	public function __construct(array $dbSettings) {
		$this->engine = $dbSettings['engine'];
		$this->host = $dbSettings['host'];
		$this->port = $dbSettings['port'];
		$this->database = $dbSettings['dbname'];
		$this->user = $dbSettings['user'];
		$this->password = $dbSettings['password'];
		$this->charset = isset($dbSettings['charset']) ? $dbSettings['charset'] : 'utf8';
		$this->connect();
	}

	public function affectedRows() {
		return $this->QueryID->rowCount();
	}

	public function totalRows() {
		return $this->QueryID->rowCount();
	}

	public function connect() {
		if($this->DBConn != null){
			$this->disconnect();
		}
		$options = array(
			\PDO::MYSQL_ATTR_FOUND_ROWS => true
		);
		try {
			$this->DBConn = new \PDO("{$this->engine}:host={$this->host};dbname={$this->database};charset={$this->charset}", $this->user, $this->password, $options);
		}
		catch (\PDOException $e) {
			$error = 	"Error {$e->getCode()}: {$e->getMessage()}";
			$this->disconnect();
			throw new DBException("Could not connect to database. {$error} {$this->engine}");
			return false;
		}
		return true;
	}

	public function disconnect() {
		$this->freeResult();
		$this->DBConn = null;
	}

	public function getRow() {
		return $this->Record;
	}

	public function nextRow() {
		$this->Record = $this->QueryID->fetch(\PDO::FETCH_ASSOC);
		if(is_array($this->Record)) {
			return true;
		}
		$this->freeResult();
		return false;

	}

	public function query($strQuery, array $params = null) {
		if(!$this->DBConn) {
			throw new DBException("Database not connected");
		}
		$this->freeResult();
		$this->Record = null;

		$this->QueryID = $this->DBConn->prepare($strQuery);

		if(!$this->QueryID) {
			throw new DBException("Error in query. {$this->getLastError()}");
		}
		$result = true;
		if(is_array($params)) {
			for($i = 0; $i < count($params); $i++) {
				$this->QueryID->bindParam($i + 1, $params[$i]['value'], $params[$i]['type']);
			}
		}
		$result =  $this->QueryID->execute();
		if(!$result)
			throw new DBException("Query failed. {$this->getLastError()}");
		return $result;
	}

    public function queryWithNamedData($strQuery, array $data)
    {
        if (!$this->DBConn) {
            throw new DBException("Database not connected");
        }
        $this->freeResult();
        $this->Record = null;

        $this->QueryID = $this->DBConn->prepare($strQuery);

        if (!$this->QueryID) {
            throw new DBException("Error in query. {$this->getLastError()}");
        }
        $result = true;
        $result =  $this->QueryID->execute($data);
        if (!$result)
            throw new DBException("Query failed. {$this->getLastError()}");
        return $result;
    }
    
	public function insertID() {
		if(!$this->DBConn) {
			throw new DBException("Database not connected");
		}

		return $this->DBConn->lastInsertId();
	}

	public function beginTransaction() {
		if(!$this->DBConn) {
			throw new DBException("Database not connected");
		}

		return $this->DBConn->beginTransaction();
	}

	public function commit() {
		if(!$this->DBConn) {
			throw new DBException("Database not connected");
		}

		return $this->DBConn->commit();
	}

	public function rollBack() {
		if(!$this->DBConn) {
			throw new DBException("Database not connected");
		}

		return $this->DBConn->rollBack();
	}

	public function getLastError() {
		$Errno = $this->QueryID != null ? $this->QueryID->errorCode() : $this->DBConn->errorCode();
		$Error = $this->QueryID != null ? $this->QueryID->errorInfo()[1] . ": " .$this->QueryID->errorInfo()[2] : $this->DBConn->errorInfo();
		return "Error {$Errno}: {$Error}";
	}

	function __destruct() {
		$this->disconnect();
	}


	private function freeResult() {
		if(is_object($this->QueryID)) {
			$this->QueryID->closeCursor();
		}
		$this->QueryID = null;
	}
}
