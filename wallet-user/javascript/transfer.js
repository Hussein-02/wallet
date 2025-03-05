document.getElementById("transferForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const email = localStorage.getItem("email");

  const receiver_wallet_id = document.getElementById("receiver_wallet_id").value;
  const amount = document.getElementById("amount").value;

  axios
    .get(`http://localhost/wallet/wallet-server/user/v1/getWalletByEmail.php?email=${email}`)
    .then((response) => {
      if (response.data.success) {
        const sender_wallet_id = response.data.wallet_id;

        const transferData = {
          sender_wallet_id: sender_wallet_id,
          receiver_wallet_id: receiver_wallet_id,
          amount: amount,
        };

        return axios.post(
          "http://localhost/wallet/wallet-server/transaction/v1/transfer.php",
          transferData
        );
      } else {
        throw new Error("Failed to retrieve sender's wallet ID.");
      }
    })
    .then((response) => {
      if (response.data.success) {
        console.log("transaction successful");
      } else {
        console.log("transaction failed");
      }
    })
    .catch((error) => {
      console.error("There was an error:", error);
    });
});
