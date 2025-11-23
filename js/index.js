const role = "admin"; // Possible roles: admin, responder, reporter, viewAll

const login = function () {
  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;

  if (username == "admin" && password == "123") {
    console.log("logged in");
    window.location.href = "main.html";
  }
};

const signout = function () {
  window.location.href = "index.html";
};

const asideButtons = function () {
  if (role === "viewAll") {
    return;
  }
  const asideDivs = document.querySelectorAll(".aside-button-container");
  asideDivs.forEach((div) => {
    if (div.id !== role) {
      div.style.display = "none";
    }
  });
};
