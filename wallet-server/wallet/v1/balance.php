<?php

include "connection/connection.php";
include "utils.php";

$wallet = new Wallet($conn);
//to recieve json from api request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["wallet_id"])) {
    $balance = $wallet->get_balance($data["wallet_id"]);
    echo json_encode(["success" => true, "balance" => $balance]);
} else {
    return_failure("invalid wallet id");
}
