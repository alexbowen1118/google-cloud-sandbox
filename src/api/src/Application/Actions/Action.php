<?php
declare(strict_types=1);

namespace DPR\API\Application\Actions;

use DPR\API\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use DPR\API\Domain\AWSAPI;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use DPR\API\Infrastructure\Persistence\DAO\DAOFactory;

ini_set('memory_limit', '2G');

abstract class Action
{
    protected LoggerInterface $logger;

    /**
     * @var DAOFactory
     */
    protected $DAOFactory;

    protected ?AWSAPI $awsAPI;

    protected Request $request;

    protected Response $response;

    protected array $args;

    public function __construct(LoggerInterface $logger, DAOFactory $DAOFactory, ?AWSAPI $awsAPI = NULL)
    {
        $this->logger = $logger;
        $this->DAOFactory = $DAOFactory;
        $this->awsAPI = $awsAPI;
    }

    /**
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            return $this->action();
        } catch (DomainRecordNotFoundException $e) {
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }

    /**
     * @throws DomainRecordNotFoundException
     * @throws HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @return array|object
     */
    protected function getFormData()
    {
        return $this->request->getParsedBody();
    }

    /**
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param array|object|null $data
     */
    protected function respondWithData($data = null, int $statusCode = 200, $headers = null): Response
    {
        $payload = new ActionPayload($statusCode, $data);

        return $this->respond($payload, $headers);
    }

    protected function respond(ActionPayload $payload, $headers = null): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        $this->response = $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($payload->getStatusCode());

        if (!is_null($headers)) {
            $key = $headers['key'];
            foreach ($headers['values'] as $discard => $value) {
                $this->response = $this->response->withAddedHeader($key, $value);
            }
        }

        return $this->response;
    }
}
