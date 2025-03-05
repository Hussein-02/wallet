document.addEventListener("DOMContentLoaded", function () {
  const token = localStorage.getItem("jwt_token");

  if (!token) {
    window.location.href = "login.html";
    return;
  }

  const logoutButton = document.querySelector("#logout");
  if (logoutButton) {
    logoutButton.addEventListener("click", function (event) {
      event.preventDefault();

      localStorage.removeItem("jwt_token");

      window.location.href = "login.html";
    });
  }
});
