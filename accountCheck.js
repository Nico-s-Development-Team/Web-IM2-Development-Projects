// Show signup modal with error message if needed
window.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const signupStatus = params.get("signup");

    if (signupStatus === "exists") {
      document.getElementById("signup").classList.remove("hidden");
      const form = document.querySelector("#signup form");
      const errorMsg = document.createElement("p");
      errorMsg.className = "text-red-500 text-sm text-center";
      errorMsg.textContent = "Email already exists. Please use a different one.";
      form.insertBefore(errorMsg, form.firstChild);
    }

    if (signupStatus === "success") {
      alert("Signup successful! Please verify your email or login.");
    }

    if (signupStatus === "error") {
      alert("An error occurred. Please try again.");
    }
  });


document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const error = urlParams.get("error");

  if (error) {
    // Show the login modal
    const loginModal = document.getElementById("login");
    loginModal.classList.remove("hidden");

    // Generate error message
    let message = "";
    switch (error) {
      case "wrong_password":
        message = "Incorrect password. Please try again.";
        break;
      case "email_not_found":
        message = "Email not found. Please sign up first.";
        break;
      case "not_verified":
        message = "Please verify your account before logging in.";
        break;
      default:
        message = "An unknown error occurred.";
    }

    // Insert error message into modal
    const form = loginModal.querySelector("form");
    const errorDiv = document.createElement("div");
    errorDiv.className = "text-red-500 text-sm text-center";
    errorDiv.textContent = message;
    form.insertBefore(errorDiv, form.firstChild);
  }
});