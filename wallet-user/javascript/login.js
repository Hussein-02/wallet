document.getElementById("loginForm").addEventListener("submit", function (event) {
  event.preventDefault();

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  axios
    .post("http://localhost/wallet/wallet-server/user/v1/login.php", {
      email: email,
      password: password,
    })
    .then(function (response) {
      if (response.data.success) {
        const token = response.data.token;
        localStorage.setItem("jwtToken", token);

        window.location.href = "/home.html";
      } else {
        alert(response.data.message);
      }
    })
    .catch(function (error) {
      console.error("There wan an error!", error);
      alert("Login failed. Please try again.");
    });
});
