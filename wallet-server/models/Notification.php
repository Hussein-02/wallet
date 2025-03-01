<?php

include "connection/connection.php";
include "utils.php";

class Notification
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function createNotification($user_id, $message)
    {
        $sql = "INSERT INTO notifications (user_id, message, status) VALUES (?, ?, 'unread')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $message);

        if ($stmt->execute()) {
            return_success();
        } else {
            return_failure($stmt->error);
        }
        $stmt->close();
    }

    public function getNotificationsByUserId($user_id)
    {
        $sql = "SELECT * FROM notifications WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            return_success();
        } else {
            return_failure($stmt->error);
        }
        $stmt->close();
    }

    public function markAsRead($notification_id)
    {
        $sql = "UPDATE notifications SET status = 'read'  WHERE notification_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $notification_id);

        if ($stmt->execute()) {
            return_success();
        } else {
            return_failure($stmt->error);
        }
        $stmt->close();
    }
}
