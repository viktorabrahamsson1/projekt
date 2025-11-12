const login = function () {
  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;

  if (username == "admin" && password == "123") {
    console.log("logged in");
    window.location.href = "main.html";
  }
};
