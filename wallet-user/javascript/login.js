document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("#loginForm");

  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    try {
      const response = await axios.post(
        "http://localhost/wallet/wallet-server/user/v1/login.php",
        {
          email: email,
          password: password,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      );

      if (response.data.success) {
        const token = response.data.token;
        localStorage.setItem("email", email);
        localStorage.setItem("token", token);

        window.location.href = "/wallet/wallet-user/home.html";
      } else {
        alert(response.data.message);
      }
    } catch (error) {
      console.error("There wan an error!", error);
      alert("Login failed. Please try again.");
    }
  });
});
