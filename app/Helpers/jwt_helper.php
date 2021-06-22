<?php

/**
 * @file.
 * To help with the generation and verification of jwt tokens.
 */

use App\Models\UserModel;
use Config\Services;
use Firebase\JWT\JWT;

/**
 * Get jwt token from request.
 * 
 * The getJWTFromRequest function checks the Authorization header 
 * of the incoming request and returns the token value
 * 
 * @param string $authenticationHeader
 * @return string $token.
 */
function getJWTFromRequest($authenticationHeader): string
{
    // JWT is absent.
    if (is_null($authenticationHeader)) {
        throw new Exception('Missing or invalid JWT in request');
    }
    // JWT is sent from client in the format Bearer XXXXXXXXX.
    return explode(' ', $authenticationHeader)[1];
}

/**
 * Validate token from request.
 * 
 * The validateJWTFromRequest function takes the token obtained by 
 * he getJWTFromRequest function. It decodes this token to get the
 * email that the key was generated for.
 * 
 * @param string $encodedToken.
 * @return void.
 */
function validateJWTFromRequest(string $encodedToken): void
{
    $key = Services::getSecretKey();
    $decodedToken = JWT::decode($encodedToken, $key, ['HS256']);
    $userModel = new UserModel();
    $userModel->findUserByEmailAddress($decodedToken->email);
}

/**
 * Get user generated token.
 * 
 * The getSignedJWTForUser function is used to generate a token 
 * for an authenticated user.
 * 
 * @param string $email. User email address.
 * @return array $jwt.
 */
function getSignedJWTForUser(string $email): array
{
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;
    $payload = [
        'email' => $email,
        'iat' => $issuedAtTime, // The time when the token was generated.
        'exp' => $tokenExpiration, // The time when the token expires.
    ];

    $jwt = JWT::encode($payload, Services::getSecretKey());
    return $jwt;
}