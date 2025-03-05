document.addEventListener("DOMContentLoaded", function () {
  const email = localStorage.getItem("email");

  if (!email) {
    window.location.href = "login.html";
    return;
  }

  axios(`http://localhost/wallet/wallet-server/wallet/v1/balance.php?email=${email}`, {
    method: "GET",
    headers: {
      Authorization: `Bearer ${localStorage.getItem("jwt_token")}`,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.balance !== undefined) {
        const balanceElement = document.querySelector(".card-balance");
        balanceElement.textContent = `${data.balance}$`;
      } else {
        console.error("Error fetching balance:", data.error);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
});
