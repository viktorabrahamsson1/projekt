window.addEventListener("DOMContentLoaded", function () {
  let selectedUserId = null;
  let firstName = "";
  let role = "";

  document.querySelectorAll(".edit-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      selectedUserId = this.dataset.userid;
      firstName = this.dataset.firstName;
      role = this.dataset.role;

      window.location.href =
        "/pages/admin/add_user.php?id=" +
        selectedUserId +
        "&first_name=" +
        firstName +
        "&role=" +
        role;
    });
  });
});
