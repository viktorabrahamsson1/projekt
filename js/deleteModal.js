document.addEventListener("DOMContentLoaded", function () {
  let selectedUserId = null;

  document.querySelectorAll(".delete-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      selectedUserId = this.dataset.userid;
      document.getElementById("deleteModal").style.display = "flex";
    });
  });

  document.getElementById("cancelDelete").onclick = () => {
    document.getElementById("deleteModal").style.display = "none";
  };

  document.getElementById("confirmDelete").onclick = () => {
    window.location.href = "/pages/admin/deleteUser.php?id=" + selectedUserId;
  };
});
