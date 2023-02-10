<?php

declare(strict_types=1);

namespace DPR\API\Infrastructure\Persistence\DAO\FileFinder;

use DPR\API\Infrastructure\Persistence\DAO\DAO;
use DPR\API\Domain\Models\Topic;
use DPR\API\Domain\Models\File;
use DPR\API\Infrastructure\Persistence\DBPDO;
use DPR\API\Infrastructure\Persistence\DBException;
use DateTime;

class SearchDAO extends DAO
{
    protected $FileBaseQuery = "SELECT * FROM ncparks.file";
    protected $TopicBaseQuery = "SELECT * FROM ncparks.topic";

    private $DOC_TYPES = "fil_dot_id";
    private $BUS_UNITS = "fil_bun_id";
    private $TAGS = "flt_tag_id";
    private $PARKS = "flp_par_id";
    private $DATE = "fil_time_uploaded";
    private $queryFileData = [];
    private $queryTopicData = [];

    function searchFiles($data)
    {
        $DBConn = $this->DBPool->request();
        $DBConn->beginTransaction();
        $topics = [];
        $files = [];
        //Handles empty search query and will return all topics
        if (empty($data)) {
            try {
                $DBConn->query($this->TopicBaseQuery . " WHERE top_active = 1");
                while ($DBConn->nextRow()) {
                    array_push($topics, new Topic($DBConn->getRow()));
                }
                $DBConn->commit();
                return array("topics" => $topics, "files" => []);
            } catch (DBException $e) {
                $DBConn->rollBack();
                $this->DBPool->release($DBConn);
                throw $e;
            }
        }

        $topicSearchQuery = $this->TopicBaseQuery;
        $fileSearchQuery = $this->FileBaseQuery;
        $tags = null;
        $parks = null;
        if (array_key_exists('tags', $data)) {
            $tags = $data['tags'];
            $fileSearchQuery .= " INNER JOIN ncparks.file_tag ON ncparks.file.fil_id = ncparks.file_tag.flt_fil_id";
        }
        if (array_key_exists('parks', $data)) {
            $parks = $data['parks'];
            $fileSearchQuery .= " INNER JOIN ncparks.file_park ON ncparks.file.fil_id = ncparks.file_park.flp_fil_id";
        }
        $fileSearchQuery .= " WHERE 1=1";
        $topicSearchQuery .= " WHERE 1=1";

        if (array_key_exists('archived', $data)) {
            $fileSearchQuery .= " AND fil_archived = ?";
            array_push($this->queryFileData, array("value" => $data['archived'], "type" => \PDO::PARAM_INT));
        } else {
            $fileSearchQuery .= " AND fil_archived = 0";
        }
        if (array_key_exists('docTypes', $data)) {
            $fileSearchQuery = $this->addSQL($data['docTypes'], $this->DOC_TYPES, $fileSearchQuery);
        }
        if (array_key_exists('busUnits', $data)) {
            $fileSearchQuery = $this->addSQL($data['busUnits'], $this->BUS_UNITS, $fileSearchQuery);
        }
        if ($tags != null && count($tags) > 0) {
            $fileSearchQuery = $this->addSQL($tags, $this->TAGS, $fileSearchQuery);
        }
        if ($parks != null && count($parks) > 0) {
            $fileSearchQuery = $this->addSQL($parks, $this->PARKS, $fileSearchQuery);
        }
        if (array_key_exists('startDate', $data)) {
            $fileSearchQuery .= " AND " . $this->DATE . " >= ?";
            array_push($this->queryFileData, array("value" => $data['startDate'], "type" => \PDO::PARAM_STR));
        }
        if (array_key_exists('endDate', $data)) {
            $fileSearchQuery .= " AND " . $this->DATE . " <= ?";
            array_push($this->queryFileData, array("value" => $data['endDate'], "type" => \PDO::PARAM_STR));
        }

        $getTopics = false;
        if (array_key_exists('keyword', $data)) {
            $keyword = $data['keyword'];
            $topicSearchQuery .= " AND MATCH(top_title, top_description) AGAINST(?)";
            array_push($this->queryTopicData, array("value" => $keyword, "type" => \PDO::PARAM_STR));
            $fileSearchQuery .= " AND fil_filename LIKE ?";
            array_push($this->queryFileData, array("value" => "%" . $keyword . "%", "type" => \PDO::PARAM_STR));
            $getTopics = true;
        }
        try {
            if ($getTopics) {
                $topicSearchQuery .= " AND top_active = 1";
                $DBConn->query($topicSearchQuery, $this->queryTopicData);
                while ($DBConn->nextRow()) {
                    array_push($topics, new Topic($DBConn->getRow()));
                }
            }

            $DBConn->query($fileSearchQuery, $this->queryFileData);

            $usedFiles = [];
            while ($DBConn->nextRow()) {
                $file = new File($DBConn->getRow());
                if (!in_array($file->getId(), $usedFiles)) {
                    array_push($files, $file);
                    array_push($usedFiles, $file->getId());
                }
            }

            $DBConn->commit();

            $output = [];
            $output["topics"] = $topics;
            $output["files"] = $files;

            return $output;
        } catch (DBException $e) {
            $DBConn->rollBack();
            $this->DBPool->release($DBConn);
            throw $e;
        }
    }

    /**
     * Takes the current SQL statement and adds a new query AND parameter
     * 
     * @param array $data The variables to be added to the query
     * @param string $type The column title for the variables in the database
     * @param string $sql The current SQL statement to be added to.
     * @return void
     */
    private function addSQL($data, $type, $sql)
    {
        $sql .= " AND";
        $length = count($data);
        $counter = 1;
        foreach ($data as $value) {
            if ($counter == 1) {
                $sql .= " (" . $type . " = ?";
            } else {
                $sql .= " OR " . $type . " = ?";
            }
            array_push($this->queryFileData, array("value" => $value, "type" => \PDO::PARAM_INT));
            if ($counter == $length) {
                $sql .= ")";
            } else {
                $counter++;
            }
        }
        return $sql;
    }
}
