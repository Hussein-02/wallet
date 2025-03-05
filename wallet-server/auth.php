<?php

$key = "12345";

// i used chatgpt to help me with this page since its the last day and there are alot of things that i must do in the project
// prompt: how to validate jwt token on protected routes 

function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function verify_jwt($token, $key)
{
    $parts = explode(".", $token);
    if (count($parts) !== 3) return false;

    list($header_encoded, $payload_encoded, $signature_encoded) = $parts;

    $signature_check = base64url_encode(hash_hmac('sha256', "$header_encoded.$payload_encoded", $key, true));

    if ($signature_check !== $signature_encoded) return false;

    $payload = json_decode(base64_decode($payload_encoded), true);

    if ($payload['exp'] < time()) return false;

    return $payload;
}

if (!isset($_SERVER["HTTP_AUTHORIZATION"])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$token = str_replace("Bearer ", "", $_SERVER["HTTP_AUTHORIZATION"]);
$userData = verify_jwt($token, $key);

if (!$userData) {
    echo json_encode(["success" => false, "message" => "Invalid or expired token"]);
    exit;
}

echo json_encode(["success" => true, "user" => $userData]);
