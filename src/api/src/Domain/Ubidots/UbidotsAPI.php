<?php
declare(strict_types=1);

namespace DPR\API\Domain\Ubidots;

use DPR\API\Domain\Models\Device;
use DPR\API\Domain\Models\Visit;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\DeviceDAO;
use Exception;

/**
 * Description of UbidotsAPI
 *
 * @author ignacioxd
 * @author Alex Bowen
 */
class UbidotsAPI
{
    //settings
    private $directory = null;
    private $ubidotsSettings = null;
    private $apikey = null;
    private $datasources_url = null;
    private $par_dev_ids = [];

    public function __construct(array $ubidotsSettings)
    {
        $this->ubidotsSettings = $ubidotsSettings;
        $this->apikey = $ubidotsSettings['apikey'];
        $this->datasources_url = $ubidotsSettings['datasources_url'];
    }

    /**
     * SeeInsights GET calls
     * @param string $datatype , "d" for devices or "v" for visits
     * @return data
     */
    public function fetchLegacyUbidotsData(string $datatype): array
    {
        //datasources
        $raw_datasources = [];
        $datasources = [];

        //variables
        $raw_variables = [];

        //visits
        $raw_visits = [];

        //data
        $visits = [];
        $devices = [];
        $data = [];

        /** datasources API calls */
        //curl to GET ubidots datasources
        $ch = curl_init();
        $headers = array();
        $headers[] = "x-auth-token: $this->apikey";
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);            // No header in the result
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result
        //raw data
        curl_setopt($ch, CURLOPT_URL, $this->datasources_url);
        $raw_datasources = curl_exec($ch);
        $raw_datasources = json_decode($raw_datasources, true);

        //create array of datasources going through every page
        while($raw_datasources) {
            foreach ($raw_datasources['results'] as $item) {
                $datasources[] = $item;
            }
            //go to next page
            curl_setopt($ch, CURLOPT_URL, $raw_datasources["next"]);
            $raw_datasources = curl_exec($ch);
            if (!is_bool($raw_datasources)) {
                $raw_datasources = json_decode($raw_datasources, true);
            }
        }

        /**  if returning device models */
        if ($datatype == "d") {
            //create device models
            for ($i = 0; $i < count($datasources); $i++) {

                //datasource for this device
                $d = $datasources[$i];

                /** parsing **/
                //parse name into usable park id and dev function
                $parsedname = explode("-", $d['name']);
                $par_id = "";
                $dev_function = "";
                if (count($parsedname) == 5) {
                    $par_id = $parsedname[2];
                    $dev_function = $parsedname[4];
                    if (strcmp($dev_function, "Car") == 0) {
                        $dev_function = 1;
                    }
                    elseif (strcmp($dev_function, "Trail") == 0) {
                        $dev_function = 2;
                    }
                } else {
                    continue;
                }
                //parse date from created-at
                $dev_date_uploaded = substr($d['created_at'], 0, 10);
                //parse latitude and longitude
                $dev_lat = 0;
                $dev_lon = 0;
                if ($d['position']) {
                    $dev_lat = (float)$d['position']['lat'];
                    $dev_lon = (float)$d['position']['lng'];
                }
                $dev_status = 1;
                if(!$d['is_active']){
                    $dev_status = 0;
                }

                //create device model
                $devices[] = new Device(array(
                    'dev_id' => NULL,
                    'dev_par_id' => $par_id,
                    'dev_number' => $d['label'],
                    'dev_name' => $d['name'],
                    'dev_function' => $dev_function,
                    'dev_type' => 4,
                    'dev_method' => 2,
                    'dev_model' => 3,
                    'dev_brand' => 2,
                    'dev_multiplier' => 1.0,
                    'dev_lat' => $dev_lat,
                    'dev_lon' => $dev_lon,
                    'dev_seeinsight_id' => $d['id'],
                    'dev_status' => $dev_status,
                    'dev_date_uploaded' => $dev_date_uploaded . " 00:00:00"
                ));
            }
            //close curl
            curl_close($ch);
            $data = $devices;

            /**  if returning visit models */
        } elseif ($datatype == "v") {

            //create visit models for all visits on each device
            for ($i = 0; $i < count($datasources); $i++) {

                //datasource for this device
                $d = $datasources[$i];
                $hourly_data_url = null;
                $raw_variables = null;

                //parse name into usable park id and dev function
                $parsedname = explode("-", $d['name']);
                $par_id = null;
                if (count($parsedname) == 5) {
                    $par_id = $parsedname[2];
                } else {
                    continue;
                }

                /** variables API call */
                //GET the variables for this device
                curl_setopt($ch, CURLOPT_URL, $d['variables_url']);
                $raw_variables = curl_exec($ch);
                $raw_variables = json_decode($raw_variables, true);

                if (!array_key_exists(8, $raw_variables["results"])) {
                    continue;
                }

                if ($hourly_data_url = $raw_variables["results"][8]["values_url"]) {
                    /** visit API calls */
                    //GET first result for hourly visitation data
                    curl_setopt($ch, CURLOPT_URL, $hourly_data_url);
                    $raw_visits = curl_exec($ch);
                    $raw_visits = json_decode($raw_visits, true);
                    //take API calls through all next pages to create remaining visit models
                    while ($raw_visits) {
                        //for each visit on the page
                        foreach ($raw_visits["results"] as $result) {
                            //if value > 0, make model
                            if ($result['value'] > 0) {
                                //make visit model
                                $visits[] = new Visit(array(
                                    'vis_id' => NULL,
                                    'vis_par_id' => $par_id,
                                    'vis_dev_id' => NULL,
                                    'vis_timestamp' => $result['timestamp'],
                                    'vis_count' => $result['value'],
                                    'vis_count_calculated' => $result['value'],

                                    /** Temporarily sends seeinsight id */
                                    'vis_comments' => $d['id'],

                                    'vis_status' => 1
                                ));
                            }
                        }

                        //go to next page
                        curl_setopt($ch, CURLOPT_URL, $raw_visits["next"]);
                        $raw_visits = curl_exec($ch);
                        if (!is_bool($raw_visits)) {
                            $raw_visits = json_decode($raw_visits, true);
                        }
                    }
                }
            }
            //close curl
            curl_close($ch);
            $data = $visits;
        }
        return $data;
    }
}

class UbidotsException extends Exception
{
    function __construct($message = '', $code = 0, $previous = null)
    {
        parent::__construct('Ubidots API Error: ' . $message, $code, $previous);
    }
}
