<?php

include "connection/connection.php";
include "utils.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    //encrypting password
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    //checking if user credentials already exist in database
    $checkUniqueStmt = $conn->prepare("SELECT email FROM users WHERE email = ? or phone = ? or username = ?");
    $checkUniqueStmt->bind_param("sss", $email, $phone, $username);
    $checkUniqueStmt->execute();
    $checkUniqueStmt->store_result();

    if ($checkUniqueStmt->num_rows > 0) {
        return_failure("username,phone number or email already exists");
    } else {
        $sql = "INSERT INTO users (username, phone, email,password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $phone, $email, $password);

        if ($stmt->execute()) {
            return_success();
        } else {
            return_failure($stmt->error);
        }
        $stmt->close();
    }
    $checkUniqueStmt->close();
    $conn->close();
}

//got help from geeksforgeeks(https://www.geeksforgeeks.org/creating-a-registration-and-login-system-with-php-and-mysql/)
