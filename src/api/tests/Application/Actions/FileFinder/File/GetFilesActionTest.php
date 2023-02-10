<?php

declare(strict_types=1);

namespace Tests\Application\Actions\FileFinder\File;

use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Domain\Models\File;
use DI\Container;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\FileDAO;
use PDOException;
use Tests\TestCase;

class GetFilesActionTest extends TestCase
{

    public function getAllFilesCases()
    {
        return array(
            # 1 active File
            array(
                array(
                    'fil_id' => 1, 'fil_top_id' => 2, 'fil_filename' => 'test.txt', 'fil_aws_s3_object' => 'uuid4-test.txt',
                    'fil_time_uploaded' => "2022-11-01 19:30:13", 'fil_uploader_id' => 10, 'fil_dot_id' => 3, 'fil_bun_id' => 5, 'fil_archived' => 1, 'fil_time_archived' => null
                ),
            ),
            # 1 archived File
            array(
                array(
                    'fil_id' => 14, 'fil_top_id' => 5, 'fil_filename' => 'test-2.txt', 'fil_aws_s3_object' => 'uuid4-test-2.txt',
                    'fil_time_uploaded' => "2022-10-05 19:30:13", 'fil_uploader_id' => 51, 'fil_dot_id' => 3, 'fil_bun_id' => 5, 'fil_archived' => 1, 'fil_time_archived' => "2022-11-03 12:32:41"
                ),
            ),
            # 5 Files active + archived
            array(
                array(
                    'fil_id' => 21, 'fil_top_id' => 5, 'fil_filename' => 'test-3.txt', 'fil_aws_s3_object' => 'uuid4-test-3.txt',
                    'fil_time_uploaded' => "2022-10-05 11:40:15", 'fil_uploader_id' => 51, 'fil_dot_id' => 3, 'fil_bun_id' => 1, 'fil_archived' => 0, 'fil_time_archived' => null
                ),
                array(
                    'fil_id' => 54, 'fil_top_id' => 4, 'fil_filename' => 'test-4.txt', 'fil_aws_s3_object' => 'uuid4-test-4.txt',
                    'fil_time_uploaded' => "2022-10-06 12:42:03", 'fil_uploader_id' => 21, 'fil_dot_id' => 3, 'fil_bun_id' => 2, 'fil_archived' => 1, 'fil_time_archived' => "2022-11-06 15:46:47"
                ),
                array(
                    'fil_id' => 242, 'fil_top_id' => 3, 'fil_filename' => 'test-5.txt', 'fil_aws_s3_object' => 'uuid4-test-5.txt',
                    'fil_time_uploaded' => "2022-10-07 13:46:16", 'fil_uploader_id' => 56, 'fil_dot_id' => 1, 'fil_bun_id' => 10, 'fil_archived' => 1, 'fil_time_archived' => "2022-11-07 12:12:39"
                ),
                array(
                    'fil_id' => 123, 'fil_top_id' => 14, 'fil_filename' => 'test-6.txt', 'fil_aws_s3_object' => 'uuid4-test-6.txt',
                    'fil_time_uploaded' => "2022-10-08 14:30:23", 'fil_uploader_id' => 103, 'fil_dot_id' => 2, 'fil_bun_id' => 5, 'fil_archived' => 0, 'fil_time_archived' => null
                ),
                array(
                    'fil_id' => 29, 'fil_top_id' => 17, 'fil_filename' => 'test-7.txt', 'fil_aws_s3_object' => 'uuid4-test-7.txt',
                    'fil_time_uploaded' => "2022-10-09 10:30:15", 'fil_uploader_id' => 1, 'fil_dot_id' => 2, 'fil_bun_id' => 5, 'fil_archived' => 0, 'fil_time_archived' => null
                ),
            ),
            # No Files
            array(array())
        );
    }

    /** 
     * @test
     * @dataProvider getAllFilesCases
     */
    public function getsAllFiles($fileData)
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $files = [];
        foreach ($fileData as $data) {
            $files[] = new File($data);
        }

        # Given a list of Files in the DB
        $fileDAOProphecy = $this->prophesize(FileDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createFileDAO()
            ->willReturn($fileDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        $fileDAOProphecy
            ->getFiles()
            ->willReturn($files)
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(FileDAO::class, $fileDAOProphecy->reveal());

        # When the GET api/filefinder/files endpoint is reached
        $request = $this->createRequest('GET', '/api/filefinder/files');
        $response = $app->handle($request);

        # All the Files from the DB are retrieved
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, ['files' => $files]);
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedExpectedPayload, $payload);
    }

    /** @test */
    public function willReturnServerError()
    {
        $app = $this->getAppInstance();
        /** @var Container $container */
        $container = $app->getContainer();

        $fileDAOProphecy = $this->prophesize(FileDAO::class);
        $daoFactoryProphecy = $this->prophesize(DAOFactory::class);
        $daoFactoryProphecy
            ->createFileDAO()
            ->willReturn($fileDAOProphecy->reveal())
            ->shouldBeCalledOnce();

        # Given that the FileDAO throws a PDOException
        $fileDAOProphecy
            ->getFiles()
            ->willThrow(new PDOException("Connection failed: SQLSTATE[HY000] [2002] Connection refused"))
            ->shouldBeCalledOnce();

        $container->set(DAOFactory::class, $daoFactoryProphecy->reveal());
        $container->set(FileDAO::class, $fileDAOProphecy->reveal());

        # When the GET api/filefinder/files endpoint is reached
        $request = $this->createRequest('GET', '/api/filefinder/files');
        $response = $app->handle($request);

        # The server responds with status code 500, and an error message
        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(500, ['message' => "Could not retrieve files: Connection failed: SQLSTATE[HY000] [2002] Connection refused"]);
        $serializedExpectedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedExpectedPayload, $payload);
    }
}
