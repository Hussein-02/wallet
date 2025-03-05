<?php

include "../../connection/connection.php";
include_once "../../utils.php";
include "../../models/Wallet.php";
include "../../models/User.php";

$userModel = new User($conn);
$walletModel = new Wallet($conn);

if (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    return_failure("email not found");
    exit;
}


$user = $userModel->getUserByEmail($email);

if ($user) {
    $wallet_id = $user['wallet_id'];

    $balance = $walletModel->get_balance($wallet_id);

    echo json_encode(["balance" => $balance]);
} else {
    return_failure("user not found");
}
