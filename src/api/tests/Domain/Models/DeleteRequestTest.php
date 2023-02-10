<?php

declare(strict_types=1);

namespace Tests\Domain\Models;

use DPR\API\Domain\Models\DeleteRequest;
use Tests\TestCase;

class DeleteRequestTest extends TestCase
{
    public function fromDatabase()
    {
        return array(
            array([
                "dlr_id" => random_int(1, 10), "dlr_fil_id" => random_int(100, 200), "dlr_requester_id" => random_int(1, 100),
                "dlr_reason" => "Outdated file", "dlr_request_time" => "2022-11-01 19:30:13"
            ]),
            array([
                "dlr_id" => random_int(1, 10), "dlr_fil_id" => random_int(100, 200), "dlr_requester_id" => random_int(1, 100),
                "dlr_reason" => "File has incorrect information", "dlr_request_time" => "2022-10-21 11:15:05"
            ]),
            array([
                "dlr_id" => random_int(1, 10), "dlr_fil_id" => random_int(100, 200), "dlr_requester_id" => random_int(1, 100),
                "dlr_reason" => "Wrong file uploaded", "dlr_request_time" => "2022-11-01 13:38:42"
            ]),
        );
    }

    public function jsonData()
    {
        return array(
            array((object) [
                "id" => random_int(1, 10), "fileId" => random_int(100, 200), "requesterId" => random_int(1, 100),
                "reason" => "Outdated file", "requestTime" => "2022-11-01 19:30:13"
            ]),
            array((object)[
                "id" => random_int(1, 10), "fileId" => random_int(100, 200), "requesterId" => random_int(1, 100),
                "reason" => "File has incorrect information", "requestTime" => "2022-10-21 11:15:05"
            ]),
            array((object)[
                "id" => random_int(1, 10), "fileId" => random_int(100, 200), "requesterId" => random_int(1, 100),
                "reason" => "Wrong file uploaded", "requestTime" => "2022-11-01 13:38:42"
            ])
        );
    }

    /**
     * @test
     * @dataProvider fromDatabase
     * @param array $data
     */
    public function constructFromDBData($data)
    {
        $deleteRequest = new DeleteRequest($data);

        $this->assertEquals($data["dlr_id"], $deleteRequest->getId());
        $this->assertEquals($data["dlr_fil_id"], $deleteRequest->getFileId());
        $this->assertEquals($data["dlr_requester_id"], $deleteRequest->getRequesterId());
        $this->assertEquals($data["dlr_reason"], $deleteRequest->getReason());
        $this->assertEquals($data["dlr_request_time"], $deleteRequest->getRequestTime());
    }

    /**
     * @test
     * @dataProvider jsonData   
     * @param array $data
     */
    public function constructFromJSON($data)
    {
        $expected = get_object_vars((object) $data);
        $deleteRequest = new DeleteRequest($data);
        $this->assertEquals($expected["id"], $deleteRequest->getId());
        $this->assertEquals($expected["fileId"], $deleteRequest->getFileId());
        $this->assertEquals($expected["requesterId"], $deleteRequest->getRequesterId());
        $this->assertEquals($expected["reason"], $deleteRequest->getReason());
        $this->assertEquals($expected["requestTime"], $deleteRequest->getRequestTime());
    }
}
