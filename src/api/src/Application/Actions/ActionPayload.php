<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions;

use JsonSerializable;

class ActionPayload implements JsonSerializable
{
    private int $statusCode;

    /**
     * @var array|object|null
     */
    private $data;

    private ?ActionError $error;

    public function __construct(
        int $statusCode = 200,
        $data = null,
        ?ActionError $error = null
    ) {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->error = $error;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array|null|object
     */
    public function getData()
    {
        return $this->data;
    }

    public function getError(): ?ActionError
    {
        return $this->error;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        $payload = $this->data ?? [];
        /*$payload = [
            'statusCode' => $this->statusCode,
        ];*/

        if ($this->data !== null) {
            //$payload['data'] = $this->data;
            $payload['result'] = "success";
        } elseif ($this->error !== null) {
            $payload['error'] = $this->error;
            $payload['result'] = "error";
        }

        return $payload;
    }
}
