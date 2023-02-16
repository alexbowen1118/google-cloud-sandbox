<?php
declare(strict_types=1);

namespace DPR\API\Application\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Slim\Psr7\Response;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Exception\HttpForbiddenException;
use Slim\Routing\RouteContext;

/**
 * Authenticates API calls from Ubidots, checks for the correct read-only key in the header.
 *
 * @param  ServerRequest  $request PSR-7 request
 * @param  RequestHandler $handler PSR-15 request handler
 *
 * @return Response
 */
class UbidotsAuthMiddleware implements Middleware
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        // Check that key in included in header
        if ($request->getHeader("x-auth-token") == null) {
            throw new HttpUnauthorizedException($request);
        }

        // Get key from header
        $requestKey = $request->getHeader("x-auth-token")[0];

        // Our key - saved as an environment variable
        $ubidotsKey = getenv('UBIDOTS_API_KEY');

        // Compare our key to request key
        if (!hash_equals($requestKey, $ubidotsKey)) {
            throw new HttpUnauthorizedException($request);
        }

        // Proceed to our webhook endpoint
        return $handler->handle($request);
    }
}
