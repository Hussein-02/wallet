<?php

include "../../connection/connection.php";
include_once "../../utils.php";

class User
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function createUser($username, $email, $phone, $password)
    {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, phone, email,password_hash) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $phone, $email, $password);

        return $stmt->execute();
    }

    public function getUserById($user_id)
    {

        if (isset($user_id)) {
            $sql = "SELECT * FROM users WHERE user_id = ?";
        } else {
            $sql = "SELECT * FROM users";
        }

        $stmt = $this->conn->prepare($sql);
        if (isset($user_id)) {
            $stmt->bind_param("i", $user_id);
        }
        return $stmt->get_result()->fetch_assoc();
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);

        return $stmt->get_result()->fetch_assoc();
    }

    public function updateUser($user_id, $username, $email, $phone)
    {
        $sql = "UPDATE users SET username = ?, email = ?, phone = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $phone, $user_id);

        return $stmt->execute();
    }

    public function deleteUser($user_id)
    {
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        return $stmt->execute();
    }

    public function getVerificationStatus($user_id)
    {
        $sql = "SELECT verification_status FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        return $stmt->get_result()->fetch_assoc();
    }

    public function uploadID($user_id, $document)
    {
        $sql = "UPDATE users SET document = ?,verification_status = 'pending' WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $document, $user_id);

        if ($stmt->execute()) {
            return_success();
        } else {
            return_failure("Error uploading document");
        }
    }
}
