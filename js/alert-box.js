document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    const alertBox = document.getElementById("alert-box");

    if (!alertBox) return;

    alertBox.classList.add("exit");

    setTimeout(() => {
      alertBox.remove();
    }, 300);
  }, 3000);
});
