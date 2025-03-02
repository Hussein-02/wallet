<?php

include "../../connection/connection.php";
include_once "../../utils.php";

$transaction = new Transaction($conn);
//to recieve json from api request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["sender_wallet_id"]) && isset($data["receiver_wallet_id"]) && isset($data["amount"])) {
    if ($transaction->transfer($data["sender_wallet_id"], $data["receiver_wallet_id"], $data["amount"])) {
        return_success();
    } else {
        return_failure("insufficient balance or error");
    }
} else {
    return_failure("invalid input");
}
