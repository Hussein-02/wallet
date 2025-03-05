<?php

include "../../connection/connection.php";
include_once "../../utils.php";
include "../../models/Transaction.php";
include "../../models/Wallet.php";


$transactionModel = new Transaction($conn);
$walletModel = new Wallet($conn);


$data = json_decode(file_get_contents("php://input"), true);


if (isset($data["sender_wallet_id"]) && isset($data["receiver_wallet_id"]) && isset($data["amount"])) {
    $sender_wallet_id = $data["sender_wallet_id"];
    $receiver_wallet_id = $data["receiver_wallet_id"];
    $amount = $data["amount"];

    $sender_balance = $walletModel->get_balance($sender_wallet_id);

    if ($sender_balance >= $amount) {
        if ($transactionModel->transfer($sender_wallet_id, $receiver_wallet_id, $amount)) {
            echo json_encode(["success" => true, "message" => "Transfer successful"]);
        } else {
            echo json_encode(["success" => false, "message" => "Transfer failed"]);
        }
    } else {
        return_failure("insufficient balance");
    }
} else {
    return_failure("invalid input");
}
