<?php

include "../../connection/connection.php";
include_once "../../utils.php";

class Wallet
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function createWallet($user_id)
    {
        $sql = "INSERT INTO wallets (user_id) VALUE (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        return $stmt->execute();
    }

    public function getWalletById($wallet_id)
    {
        $sql = "SELECT * FROM wallets WHERE wallet_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $wallet_id);

        return $stmt->get_result()->fetch_assoc();
    }

    public function updateWallet($wallet_id, $balance)
    {
        $sql = "UPDATE walltes SET balance = ?  WHERE wallet_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("di", $balance, $wallet_id);

        return $stmt->execute();
    }

    public function deleteWallet($wallet_id)
    {
        $sql = "DELETE FROM wallets WHERE wallet_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $wallet_id);

        return $stmt->execute();
    }


    //to return wallet balance
    public function get_balance($wallet_id)
    {
        $sql = "SELECT balance FROM wallets WHERE wallet_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $wallet_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['balance'] ?? 0;
    }
}
