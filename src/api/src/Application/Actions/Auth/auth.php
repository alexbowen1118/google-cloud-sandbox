<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;
use DPR\API\Infrastructure\Persistence\DB;

if (!defined('COOKIE_TTL')) {
    // Valid time of cookies in seconds.
    define('COOKIE_TTL', 60 * 60 * 24);
}

if (!function_exists('getToken')) {

    # Gets user role
    function getToken(Request $request, Response $response)
    {
        $requestBody = $request->getBody();
        $parsed_request = json_decode($requestBody);
        $username = $parsed_request->username;
        $password = $parsed_request->password;
        try {
            $sql = "SELECT hash, role FROM users where user='$username'";
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->query($sql);
            $result = $stmt->fetchAll();
            $db = null;
        } catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );

            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(500);
        }
        // Check password against stored hash which includes hashed password, algorith, salt, and cost.
        if (count($result) > 0 && password_verify($password, $result[0]["hash"])) {
            // Valid password

            // The algorithm manager with the HS256 algorithm.
            $algorithmManager = new AlgorithmManager([
                new HS256(),
            ]);

            // Our key.
            $jwk = JWK::createFromJson(file_get_contents("/run/secrets/jwt_key.json"));

            // The payload we want to sign. The payload MUST be a string hence we use our JSON Converter.
            $payload = json_encode([
                'sub' => $username,
                'role' => $result[0]["role"]
            ]);

            // We instantiate our JWS Builder.
            $jwsBuilder = new JWSBuilder($algorithmManager);

            $jws = $jwsBuilder
                ->create()                               // We want to create a new JWS
                ->withPayload($payload)                  // We set the payload
                ->addSignature($jwk, ['alg' => 'HS256']) // We add a signature with a simple protected header
                ->build();                               // We build it

            $serializer = new CompactSerializer(); // The serializer
            $token = $serializer->serialize($jws, 0); // We serialize the signature at index 0 (we only have one signature).
            $tokenParts = explode(".", $token);
            $ttl = date(DATE_RFC7231, time() + COOKIE_TTL);
            $jwtHeaderCookie = "jwtHeader=${tokenParts[0]}; SameSite=Strict; Secure; Path=/; Expires=${ttl}";
            $jwtPayloadCookie = "jwtPayload=${tokenParts[1]}; SameSite=Strict; Secure; Path=/; Expires=${ttl}";
            $jwtSignatureCookie = "jwtSignature=${tokenParts[2]}; SameSite=Strict; Secure; HttpOnly; Path=/api/; Expires=${ttl}";
            $response->getBody()->write(json_encode(array("response" => "Logged in")));
            $result = $response->withHeader("content-type", "application/json")->withAddedHeader("Set-Cookie", $jwtHeaderCookie)
                ->withAddedHeader("Set-Cookie", $jwtPayloadCookie)->withAddedHeader("Set-Cookie", $jwtSignatureCookie);

            return $result;
        } else {
            // Invalid password;
            $response->getBody()->write(json_encode(array("response" => "Invalid username or password")));
            return $response->withStatus(401)->withHeader("content-type", "application/json");
        }
    };
}
