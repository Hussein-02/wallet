<?php

include "connection/connection.php";
include "utils.php";

$notification = new Notification($conn);
//to recieve json from api request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["notification_id"])) {
    if ($notification->markAsRead($data["notification_id"])) {
        return_success();
    } else {
        return_failure("marking as read failed");
    }
} else {
    return_failure("invalid user id");
}
