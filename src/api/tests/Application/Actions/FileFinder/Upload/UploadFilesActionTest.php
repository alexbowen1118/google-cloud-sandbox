<?php

declare(strict_types=1);

namespace Tests\Application\Actions\FileFinder\Upload;

use DPR\API\Application\Actions\ActionPayload;
use DI\Container;
use DPR\API\Domain\AWSAPI;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\UploadDAO;
use Exception;
use Prophecy\Argument;
use Slim\Psr7\UploadedFile;
use Tests\TestCase;

class UploadFilesActionTest extends TestCase
{

    private static function getMockFile($fileName)
    {
        $file = new UploadedFile(__FILE__, $fileName);
        return $file;
    }

    public function uploadMultipleCase()
    {
        $singleTestData = array(
            "metadata" => null,
            "files" => []
        );
        $metadata = [
            "topic" => [
                "title" => "Test Title",
                "description" => "Test Description"
            ],
            "uploader_id" => random_int(1, 50),
            "files" => []
        ];
        for ($i = 0; $i < 2; $i++) {
            $singleTestData["files"][] = UploadFilesActionTest::getMockFile("test-$i.txt");
            $metadata["files"][] = [
                "filename" => "test-$i.txt",
                "business_unit_id" => random_int(1, 10),
                "document_type_id" => random_int(1, 3),
                "tags" => [
                    random_int(1, 10),
                    random_int(11, 20),
                ],
                "parks" => [
                    random_int(1, 100),
                    random_int(101, 200)
                ]
            ];
        }
        $singleTestData["metadata"] = (object) $metadata;
        $data = array(
            array(
                $singleTestData
            )
        );
        return $data;
    }

    public function uploadSingleCases()
    {
        $data = array();
        for ($i = 0; $i < 2; $i++) {
            $file = UploadFilesActionTest::getMockFile("test-$i.txt");
            $metadata = (object) [
                "topic" => [
                    "title" => "Test Title $i",
                    "description" => "Test Description $i"
                ],
                "uploader_id" => random_int(1, 50),
                "files" => [
                    [
                        "filename" => "test-$i.txt",
                        "business_unit_id" => random_int(1, 10),
                        "document_type_id" => random_int(1, 3),
                        "tags" => [
                            random_int(1, 10),
                            random_int(11, 20),
                        ],
                        "parks" => [
                            random_int(1, 100),
                            random_int(101, 200)
                        ]
                    ]
                ]
            ];
            $data[] = array(
                array(
                    "metadata" => $metadata,
                    "files" => [$file],
                )
            );
        }
        return $data;
    }

    public function invalidMetadataCases()
    {
        $file = UploadFilesActionTest::getMockFile('test.txt');
        $metadataNoFiles = (object) [
            "topic" => [
                "title" => "Test Title",
                "description" => "Test Description"
            ],
            "uploader_id" => random_int(1, 50),
        ];
        $metadataFilesNoAttributes = (object) [
            "topic" => [
                "title" => "Test Title",
                "description" => "Test Description"
            ],
            "uploader_id" => random_int(1, 50),
            "files" => [
                (object) []
            ]
        ];
        $data = array(
            array(
                ["metadata" => $metadataNoFiles, "test.txt" => $file]
            ),
            array(
                ["metadata" => $metadataFilesNoAttributes, "test.txt" => $file]
            )
        );
        return $data;
    }

    /** 
     * @test
     * @dataProvider uploadSingleCases
     * @dataProvider uploadMultipleCase
     */
    public function uploadFiles($testData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        # Given a valid file and associated metadata
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $uploadDAOProphecy = $this->prophesize(UploadDAO::class);
        $awsAPIProphecy = $this->prophesize(AWSAPI::class);

        $daoFactoryProphecy
            ->createUploadDAO()
            ->willReturn($uploadDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $uploadDAOProphecy
            ->uploadFiles(Argument::type('array'))
            ->shouldBeCalledOnce();

        $awsAPIProphecy
            ->putObject(Argument::type('string'), Argument::type('string'))
            ->shouldBeCalledTimes(sizeof($testData["metadata"]->files));

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(UploadDAO::class, $uploadDAOProphecy->reveal());
        $container->set(AWSAPI::class, $awsAPIProphecy->reveal());

        # When the POST api/filefinder/upload endpoint is reached with the file(s) and metadata
        $requestHeaders = [
            'HTTP_ACCEPT' => 'application/json',
        ];
        $request = $this->createRequest('POST', '/api/filefinder/upload', $requestHeaders);
        $request = $request->withUploadedFiles($testData["files"]);
        $request = $request->withParsedBody(["metadata" => json_encode($testData["metadata"])]);
        $response = $app->handle($request);

        # Then a success response is returned
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload();
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);
        $this->assertEquals($serializedExpectedPayload, $payload);
    }

    /** 
     * @test
     * @dataProvider uploadSingleCases
     */
    public function metadataNotJSONEncoded($testData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $uploadDAOProphecy = $this->prophesize(UploadDAO::class);
        $awsAPIProphecy = $this->prophesize(AWSAPI::class);

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(UploadDAO::class, $uploadDAOProphecy->reveal());
        $container->set(AWSAPI::class, $awsAPIProphecy->reveal());

        $requestHeaders = [
            'HTTP_ACCEPT' => 'application/json',
        ];
        $request = $this->createRequest('POST', '/api/filefinder/upload', $requestHeaders);
        $request = $request->withUploadedFiles($testData["files"]);
        # Given metadata that is not JSON Encoded
        $request = $request->withParsedBody(["metadata" => $testData["metadata"]]);
        # When the POST api/filefinder/upload endpoint is reached with the file(s) and metadata
        $response = $app->handle($request);

        # Then an error response is returned
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(400, ["message" => "Request body format is incorrect!"]);
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);
        $this->assertEquals($serializedExpectedPayload, $payload);
    }

    /** 
     * @test
     * @dataProvider invalidMetadataCases
     */
    public function invalidMetadata($testData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $uploadDAOProphecy = $this->prophesize(UploadDAO::class);
        $awsAPIProphecy = $this->prophesize(AWSAPI::class);

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(UploadDAO::class, $uploadDAOProphecy->reveal());
        $container->set(AWSAPI::class, $awsAPIProphecy->reveal());

        $requestHeaders = [
            'HTTP_ACCEPT' => 'application/json',
        ];
        $request = $this->createRequest('POST', '/api/filefinder/upload', $requestHeaders);
        $request = $request->withUploadedFiles([$testData["test.txt"]]);
        # Given metadata that is invalid
        $request = $request->withParsedBody(["metadata" => json_encode($testData["metadata"])]);
        # When the POST api/filefinder/upload endpoint is reached with the file(s) and metadata
        $response = $app->handle($request);

        # Then an error response is returned
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(400, ["message" => "Request body format is incorrect: File metadata cannot be read!"]);
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);
        $this->assertEquals($serializedExpectedPayload, $payload);
    }

    /** 
     * @test
     * @dataProvider uploadMultipleCase
     */
    public function S3Error($testData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $uploadDAOProphecy = $this->prophesize(UploadDAO::class);
        $awsAPIProphecy = $this->prophesize(AWSAPI::class);

        # Given that the S3 upload would fail for the second file
        $awsAPIProphecy
            ->putObject(Argument::type('string'), Argument::type('string'))
            ->will(function ($args, $awsAPIProphecy) {
                $awsAPIProphecy
                    ->putObject(Argument::type('string'), Argument::type('string'))
                    ->willThrow(new Exception("Key not allowed!"));
            });

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(UploadDAO::class, $uploadDAOProphecy->reveal());
        $container->set(AWSAPI::class, $awsAPIProphecy->reveal());

        # When the POST api/filefinder/upload endpoint is reached with the file(s) and metadata
        $requestHeaders = [
            'HTTP_ACCEPT' => 'application/json',
        ];
        $request = $this->createRequest('POST', '/api/filefinder/upload', $requestHeaders);
        $request = $request->withUploadedFiles($testData["files"]);
        $request = $request->withParsedBody(["metadata" => json_encode($testData["metadata"])]);
        $response = $app->handle($request);

        # Then an error response is returned
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(500, ["message" => "Key not allowed!"]);
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);
        $this->assertEquals($serializedExpectedPayload, $payload);

        $awsAPIProphecy
            ->putObject(Argument::type('string'), Argument::type('string'))
            ->shouldHaveBeenCalledTimes(2);

        $awsAPIProphecy
            ->deleteObjects(Argument::type('array'))
            ->shouldHaveBeenCalledOnce();

        $daoFactoryProphecy
            ->createUploadDAO()
            ->shouldNotHaveBeenCalled();

        $uploadDAOProphecy
            ->uploadFiles(Argument::any())
            ->shouldNotHaveBeenCalled();
    }
}
