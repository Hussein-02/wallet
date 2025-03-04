<?php

include "../../connection/connection.php";
include_once "../../utils.php";

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

    //to get transaction history
    public function getTransactions($wallet_id)
    {
        $sql = "SELECT * FROM transactions WHERE wallet_id=? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $wallet_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteTransaction($transaction_id)
    {
        $sql = "DELETE FROM transactions WHERE transaction_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $transaction_id);

        return $stmt->execute();
    }

    public function deposit($wallet_id, $amount)
    {
        $sql = "UPDATE wallets SET balance = balance + ? WHERE wallet_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("di", $amount, $wallet_id);
        return $stmt->execute();
    }

    public function withdraw($wallet_id, $amount)
    {
        $sql = "UPDATE wallets SET balance = balance - ? WHERE wallet_id=? AND balance>= ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("dii", $amount, $wallet_id, $amount);
        return $stmt->execute();
    }

    public function transfer($sender_wallet_id, $receiver_wallet_id, $amount)
    {
        //to not commit to database until i call commit(such that not one query happen and somthing stops it so the sender would lose the money for nothing)
        $this->conn->begin_transaction();
        //i didnt use the previous withdraw and deposit here because they return
        try {
            //remove from sender
            $sql1 = "UPDATE wallets SET balance = balance - ? WHERE wallet_id=? AND balance>= ?";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->bind_param("dii", $amount, $sender_wallet_id, $amount);
            $stmt1->execute();

            //give to receiver
            $sql2 = "UPDATE wallets SET balance = balance + ? WHERE wallet_id=?";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->bind_param("di", $amount, $receiver_wallet_id);
            $stmt2->execute();

            $this->conn->commit();
            return_success();
        } catch (Exception $e) {
            $this->conn->rollback();
            return_failure("transfer failed");
        }
    }
}
