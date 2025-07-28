<?php
require_once 'vendor/autoload.php';
require_once 'vault.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$JWT_SECRET = get_jwt_secret_from_vault();

function generate_jwt($data) {
    global $JWT_SECRET;

    $payload = array_merge($data, [
        'iat' => time(),              // Issued At
        'exp' => time() + 3600        // Expire dans 1h
    ]);

    return JWT::encode($payload, $JWT_SECRET, 'HS256');
}

function validate_jwt($token) {
    global $JWT_SECRET;
    return JWT::decode($token, new Key($JWT_SECRET, 'HS256'));
}
