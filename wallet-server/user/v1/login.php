<?php

include "../../connection/connection.php";
include_once "../../utils.php";
include "../../models/Wallet.php"; // Include the Wallet model

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

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (!$data) {
        echo json_encode(["success" => false, "message" => "Invalid JSON input"]);
        exit;
    }

    $email = $data['email'];
    $password = $data['password'];

    $sql = "SELECT user_id, email, phone, password_hash, role, username FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $email, $phone, $hashed_password, $role, $username);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {

            $walletModel = new Wallet($conn);
            $wallet_check_sql = "SELECT wallet_id FROM wallets WHERE user_id = ?";
            $wallet_check_stmt = $conn->prepare($wallet_check_sql);
            $wallet_check_stmt->bind_param("i", $id);
            $wallet_check_stmt->execute();
            $wallet_check_stmt->store_result();

            if ($wallet_check_stmt->num_rows === 0) {
                $walletModel->createWallet($id);
            }
            $wallet_check_stmt->close();


            $form = [
                "user_id" => $id,
                "email" => $email,
                "role" => $role,
                "exp" => time() + 60 * 60
            ];

            $token = generate_jwt($form, $key);

            echo json_encode(["success" => true, "token" => $token, "user" => [
                "id" => $id,
                "email" => $email,
                "username" => $username,
                "role" => $role
            ]]);
        } else {
            return_failure("incorrect password");
        }
    } else {
        return_failure("email not found");
    }
    $stmt->close();
    $conn->close();
}
