<?php

declare(strict_types=1);

namespace DPR\API\Application\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;

use DPR\API\Application\Actions\Action;
use DPR\API\Application\Actions\ActionPayload;
use DPR\API\Application\Actions\ActionError;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;

use Exception;
use Slim\Exception\HttpForbiddenException;

class GetAuthenticationTokenAction extends Action
{

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
    
        $data = $this->getFormData();

        try {

            // Get username and password
            [$username, $password] = $this->validateRequestBody($data);

            // Check username and password
            $this->authenticateUser($username, $password);

            // Check that the user has access to the application
            $authDAO = $this->DAOFactory->createAuthenticationDAO();
            $role = $authDAO->getUserAppRoleId($username);
            if ($role < 1) {
                // If user app role is 0, unauthorized to enter application
                throw new HttpForbiddenException($this->request);
            }
            
            $tokens = $this->generateAuthTokens($username);
            $headers = [];
            $headers['key'] = 'Set-Cookie';
            $headers['values'] = $tokens;
            foreach ($tokens as $index => $value) {
                $headers['values'][] = $value;
            }

            $this->logger->info("User ${username} was authorized.");

            $result = $this->respondWithData(array("Response" => "Logged in", "User" => $username, "User Role" => $authDAO->getUserAppRole($username), 
                    "User Park" => $authDAO->getUserPark($username)), 200, $headers);
            return $result;
        } catch (Exception $e) {
            if ($e instanceof HttpForbiddenException) {
                return $this->respond(new ActionPayload(403, null, new ActionError(ActionError::INSUFFICIENT_PRIVILEGES, $e->getMessage())));
            }

            return $this->respond(new ActionPayload(401, null, new ActionError(ActionError::UNAUTHENTICATED, $e->getMessage())));
        }
    }

    private function validateRequestBody($data)
    {
        $username = $data['username'];
        $password = $data['password'];
        if (!is_string($username) || !is_string($password)) {
            throw new Exception("Request body format is incorrect.");
        }
        return [$username, $password];
    }

    private function authenticateUser($username, $password)
    {
        $authDAO = $this->DAOFactory->createAuthenticationDAO();
        try {
            $hash = $authDAO->getHash($username);
        } catch (Exception $e) {
            throw new Exception("Invalid username or password.");
        }
        if (!password_verify($password, $hash)) {
            throw new Exception("Invalid username or password.");
        }
    }

    private function generateAuthTokens($username)
    {
        $algorithmManager = new AlgorithmManager([
            new HS256(),
        ]);

        // Our key.
        $jwk = JWK::createFromJson(file_get_contents("/run/secrets/jwt_key.json"));

        $authDAO = $this->DAOFactory->createAuthenticationDAO();

        // The payload we want to sign. The payload MUST be a string hence we use our JSON Converter.
        // Includes user's username, app role level, and associated park
        $payload = json_encode([
            'sub' => $username,
            'role' => $authDAO->getUserAppRole($username),
            'park' => $authDAO->getUserPark($username)
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
    
        return [$jwtHeaderCookie, $jwtPayloadCookie, $jwtSignatureCookie];
    }

}
