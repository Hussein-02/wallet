<?php

include "../../connection/connection.php";
include_once "../../utils.php";
include "../../models/User.php";

$key = "12345";

function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function generate_jwt($payload, $key)
{
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $header_encoded = base64url_encode($header);

    $payload_encoded = base64url_encode(json_encode($payload));

    $signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $key, true);
    $signature_encoded = base64url_encode($signature);

    return "$header_encoded.$payload_encoded.$signature_encoded";
}


$usermodel = new User($conn);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (!$data) {
        return_failure("Invalid JSON input");
    }

    $username = $data['username'];
    $phone = $data['phone'];
    $email = $data['email'];
    //encrypting password
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    //checking if user credentials already exist in database
    $checkUniqueStmt = $conn->prepare("SELECT email FROM users WHERE email = ? or phone = ? or username = ?");
    $checkUniqueStmt->bind_param("sss", $email, $phone, $username);
    $checkUniqueStmt->execute();
    $checkUniqueStmt->store_result();

    if ($checkUniqueStmt->num_rows > 0) {
        return_failure("username,phone number or email already exists");
    } else {
        if ($usermodel->createUser($username, $email, $phone, $password)) {
            $user_id = $conn->insert_id;

            $token_payload = [
                "user_id" => $user_id,
                "email" => $email,
                "username" => $username,
                "role" => "user",
                "exp" => time() + 60 * 60
            ];

            $token = generate_jwt($token_payload, $key);

            echo json_encode([
                "success" => true,
                "message" => "Registration successful",
                "token" => $token,
                "user" => [
                    "id" => $user_id,
                    "email" => $email,
                    "username" => $username,
                    "role" => "user"
                ]

            ]);
        } else {
            return_failure("error registering user");
        }
    }
    $checkUniqueStmt->close();
    $conn->close();
}

//got help from geeksforgeeks(https://www.geeksforgeeks.org/creating-a-registration-and-login-system-with-php-and-mysql/)
