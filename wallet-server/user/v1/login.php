<?php

include "connection/connection.php";
include "utils.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, email, phone, password_hash, role, username FROM userdata WHERE email = ? or username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $email, $phone, $hashed_password, $role, $username);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION["user_id"] = $id;
            $_SESSION["email"] = $email;
            $_SESSION["phone"] = $phone;
            $_SESSION["role"] = $role;
            $_SESSION["username"] = $username;

            return_success();
        } else {
            return_failure("incorrect password");
        }
    } else {
        return_failure("email or username not found");
    }
    $stmt->close();
    $conn->close();
}
