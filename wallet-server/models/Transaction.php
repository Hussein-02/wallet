<?php

include "connection/connection.php";
include "utils.php";

class Transaction
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function createTransaction($wallet_id, $amount, $transaction_type, $reference_id = null)
    {
        $sql = "INSERT INTO transactions (wallet_id,amount,transaction_type,reference_id) VALUE (?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("idss", $wallet_id, $amount, $transaction_type, $reference_id);

        return $stmt->execute();
    }

    public function getTransactionsByWalletId($wallet_id)
    {
        $sql = "SELECT * FROM transactions WHERE wallet_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $wallet_id);

        return $stmt->get_result();
    }

    public function deleteTransaction($transaction_id)
    {
        $sql = "DELETE FROM transactions WHERE transaction_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $transaction_id);

        return $stmt->execute();
    }
}
