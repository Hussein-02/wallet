<?php

include "connection/connection.php";
include "utils.php";

$notification = new Notification($conn);
//to recieve json from api request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["user_id"])) {
    if ($notification->createNotification($data["user_id"], $data["message"])) {
        return_success();
    } else {
        return_failure("notification failed");
    }
} else {
    return_failure("invalid user id");
}
