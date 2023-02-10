<?php

declare(strict_types=1);

namespace DPR\API\Domain;

use \Ubidots\ApiClient;

/**
 * Description of UbidotsAPI
 *
 * @author ignacioxd
 */
class UbidotsAPI
{
    private $ubidotsSettings = null;
    private $apiClient = null;

    public function __construct(array $ubidotsSettings)
    {
        $this->ubidotsSettings = $ubidotsSettings;
        $this->apiClient = new ApiClient($apikey = $this->ubidotsSettings['api_key']);
    }

    function getDevices()
    {
        //Update as needed
        try {
            return ['device 1', 'device 2'];
            //$this->apiClient->get_datasources();
        } catch (\Exception $e) {
            throw new UbidotsException($e->getMessage(), $e->getCode(), $e);
        }
    }
}

class UbidotsException extends \Exception
{
    function __construct($message = '', $code = 0, $previous = null)
    {
        parent::__construct('Ubidots API Error: ' . $message, $code, $previous);
    }
}
