document.getElementById("transfer-form").addEventListener("submit", function (event) {
  event.preventDefault();

  const receiver = document.getElementById("receiver").value;
  const amount = parseFloat(document.getElementById("amount").value);
  const note = document.getElementById("note").value;

  const sender_wallet_id = localStorage.getItem("wallet_id");

  if (!sender_wallet_id) {
    alert("No wallet found for sender.");
    return;
  }

  axios(`/path/to/getReceiverWallet.php?username=${receiver}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.wallet_id) {
        const receiver_wallet_id = data.wallet_id;

        axios("http://localhost/wallet/wallet-server/wallet/v1/transfer.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            sender_wallet_id: sender_wallet_id,
            receiver_wallet_id: receiver_wallet_id,
            amount: amount,
            note: note,
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              alert(data.message);
            } else {
              alert("Error: " + data.message);
            }
          });
      } else {
        alert("Receiver not found.");
      }
    });
});
