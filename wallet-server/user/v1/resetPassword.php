<?php

include "connection/connection.php";
include "utils.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password === $confirmPassword) {
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $password, $email);

        if ($stmt->execute()) {
            return_success();
        } else {
            return_failure("Error updating password");
        }

        $stmt->close();
    } else {
        return_failure("passwords do not match");
    }
    $conn->close();
}
