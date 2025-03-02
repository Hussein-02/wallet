<?php

include "../../connection/connection.php";
include_once "../../utils.php";
include "../../models/Notification.php";

$notification = new Notification($conn);
//to recieve json from api request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["user_id"])) {
    $notifications = $notification->getNotifications($data["user_id"]);
    echo json_encode(["success" => true, "notifications" => $notifications]);
} else {
    return_failure("invalid user id");
}
