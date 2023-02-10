<?php
declare(strict_types=1);

namespace DPR\API\Application\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\JWSVerifier;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Exception\HttpForbiddenException;
use Slim\Routing\RouteContext;


function parseJWT(Request $request)
{
    // Parse the passed cookies.
    $cookieStr = $request->getHeader("Cookie")[0];
    parse_str(strtr($cookieStr, array('&' => '%26', '+' => '%2B', ';' => '&')), $cookies);

    // Check cookie key membership for JWT elements.
    if (count(array_intersect_key(array("jwtHeader", "jwtPayload", "jwtSignature"), array_keys($cookies))) < 3) {
        throw new HttpUnauthorizedException($request);
    }

    // Recombine the JWT.
    $jwt = $cookies["jwtHeader"] . "." . $cookies["jwtPayload"] . "." . $cookies["jwtSignature"];

    // The serializer manager. We only use the JWS Compact Serialization Mode.
    $serializerManager = new JWSSerializerManager([
        new CompactSerializer(),
    ]);

    // We try to load the token.
    $jws = $serializerManager->unserialize($jwt);

    return $jws;
}


/**
 * JWT authentication middleware.
 *
 * @param  ServerRequest  $request PSR-7 request
 * @param  RequestHandler $handler PSR-15 request handler
 *
 * @return Response
 */
class TokenAuthMiddleware implements Middleware
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        // The algorithm manager with the HS256 algorithm.
        $algorithmManager = new AlgorithmManager([
            new HS256(),
        ]);

        // We instantiate our JWS Verifier.
        $jwsVerifier = new JWSVerifier(
            $algorithmManager
        );

        // Our key.
        $jwk = JWK::createFromJson(file_get_contents("/run/secrets/jwt_key.json"));

        // Parse the JWT from the request headers.
        $jws = parseJWT($request);

        // We verify the signature. This method does NOT check the header.
        // The arguments are:
        // - The JWS object,
        // - The key,
        // - The index of the signature to check. See
        $isVerified = $jwsVerifier->verifyWithKey($jws, $jwk, 0);
        if (!$isVerified) {
            throw new HttpUnauthorizedException($request);
        }

        // Parse claims from the JWT.
        $claims = json_decode($jws->getPayload(), true);

        // Parse the role from the JWT.
        if (!array_key_exists("role", $claims)) {
            throw new HttpForbiddenException($request);
        }
        $role = $claims["role"];

        // Extract the route object from the request.
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        // Retrieve the permissions needed to access this route.
        $permissions = array_map("trim", explode(",", $route->getArgument("permissions", "")));

        // Attach parsed JWT info to the request for next method use.
        // $request = $request->withAttribute('claims', json_encode($claims));
        $request = $request->withAttribute('claims', json_encode($claims));

        // Verifies that JWT's role is included in the permissions of the endpoint or the endpoint is unrestricted.
        if (in_array($role, $permissions) || in_array("ALL", $permissions)) {
            // Handle Pass the request on to the method.
            $response = $handler->handle($request);
            return $response;
        } else {
            throw new HttpForbiddenException($request);
        }

        return $handler->handle($request);
    }
}
