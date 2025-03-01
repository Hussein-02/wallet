<?php

include "connection/connection.php";
include "utils.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, email, password_hash, role FROM users WHERE email = ? AND role = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $email, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION["user_id"] = $id;
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $role;

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
