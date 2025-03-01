<?php

include "connection/connection.php";
include "utils.php";

$transaction = new Transaction($conn);
//to recieve json from api request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["wallet_id"])) {
    $transactions = $transaction->getTransactions($data["wallet_id"]);
    echo json_encode(["success" => true, "transactions" => $transactions]);
} else {
    return_failure("invalid wallet id");
}
