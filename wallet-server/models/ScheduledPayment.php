<?php

include "connection/connection.php";
include "utils.php";

class ScheduledPayment
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function createScheduledPayment($wallet_id, $recipient_wallet_id, $amount, $schedule_type, $next_run)
    {
        $sql = "INSERT INTO scheduled_payments (wallet_id, recipient_wallet_id,amount,scheduled_type,next_run) VALUES (?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisss", $wallet_id, $recipient_wallet_id, $amount, $schedule_type, $next_run);

        if ($stmt->execute()) {
            return_success();
        } else {
            return_failure($stmt->error);
        }
        $stmt->close();
    }

    public function getScheduledPayments($wallet_id)
    {
        $sql = "SELECT * FROM scheduled_payments WHERE wallet_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $wallet_id);

        if ($stmt->execute()) {
            return_success();
        } else {
            return_failure($stmt->error);
        }
        $stmt->close();
    }

    public function updateStatus($scheduled_id, $status)
    {
        $sql = "UPDATE scheduled_payments SET status = ?  WHERE scheduled_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $scheduled_id);

        if ($stmt->execute()) {
            return_success();
        } else {
            return_failure($stmt->error);
        }
        $stmt->close();
    }

    public function deleteScheduledPayment($scheduled_id)
    {
        $sql = "DELETE FROM scheduled_payments WHERE scheduled_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $scheduled_id);

        if ($stmt->execute()) {
            return_success();
        } else {
            return_failure($stmt->error);
        }
        $stmt->close();
    }
}
