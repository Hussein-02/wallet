<?php
include "../../connection/connection.php";
include_once "../../models/Wallet.php";
include_once "../../utils.php";

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $sql = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['user_id'];

        $walletModel = new Wallet($conn);
        $wallet = $walletModel->getWalletByUserId($user_id);

        if ($wallet) {
            echo json_encode([
                'success' => true,
                'wallet_id' => $wallet['wallet_id']
            ]);
        } else {
            return_failure("no wallet found");
        }
    } else {
        return_failure("no user found");
    }
} else {
    return_failure("email not provided");
}
