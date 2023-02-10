<?php

declare(strict_types=1);

namespace Tests;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

/**
 * Authentication middleware mock.
 *
 * @param  ServerRequest  $request PSR-7 request
 * @param  RequestHandler $handler PSR-15 request handler
 *
 * @return Response
 */
class TokenAuthMiddlewareMock implements Middleware
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        return $handler->handle($request);
    }
}
