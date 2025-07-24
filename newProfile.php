<?php
include 'fetch_profile.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nico's Delivery - Profile</title>
  <link rel="stylesheet" href="newProfileStyle.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Libre+Franklin:ital,wght@0,100..900;1,100..900&family=Open+Sans:wght@400;500&display=swap&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900" rel="stylesheet">
</head>
<body> 
<div class="wrapper">

  <div class="profile-div-row">
    <a href="orderPage.php" class="backBtn">&#8249;</a>
    <p class="profile-row-greeting">Welcome Back! <?= htmlspecialchars($user['Customer_FirstName']) ?></p>
  </div>

  <div class="profile-container">

    <div class="profile-div-left">
      <div class="profile-types">
        <img src="img/user-image.jpeg" alt="User Image" class="profile-user-image">
        <p class="profile-user-name-view"><?= htmlspecialchars($user['Customer_FirstName']) . ' ' . htmlspecialchars($user['Customer_LastName']) ?></p>
        <p class="profile-user-email-view"><?= htmlspecialchars($user['Email']) ?></p>

        <div class="change-profile-pic-container">
          <h2>Change profile picture?</h2>
          <input type="file" id="profile-pic-input" accept="image/*">
          <button id="upload-button">Upload</button>
        </div>
      </div>
    </div>

    <div class="profile-div-middle-main">
      <div class="profile-div-center">
        <div class="profile-types">
          <div class="profile-types-div-row">
            <p class="profile-category-name">Profile</p>
            <p class="profile-category-description">View and update your profile information.</p>
          </div>

          <div class="profile-types-div-left">
            <div class="profile-form-group floating-label">
              <input type="text" id="first-name" placeholder=" " value="<?= htmlspecialchars($user['Customer_FirstName']) ?>" required>
              <label for="first-name">First Name</label>
            </div>

            <div class="profile-form-group floating-label">
              <input type="text" id="email" placeholder=" " value="<?= htmlspecialchars($user['Email']) ?>" required>
              <label for="email">Email</label>
            </div>

            <div class="profile-form-group floating-label">
              <input type="password" id="password" placeholder=" " value="<?= htmlspecialchars($user['Password']) ?>" required>
              <label for="password">Password</label>
            </div>
          </div>

          <div class="profile-types-div-right">
            <div class="profile-form-group floating-label">
              <input type="text" id="last-name" placeholder=" " value="<?= htmlspecialchars($user['Customer_LastName']) ?>" required>
              <label for="last-name">Last Name</label>
            </div>

            <div class="profile-form-group floating-label">
              <input type="tel" id="mobile-num" placeholder=" " value="<?= htmlspecialchars($user['MobileNumber']) ?>" required>
              <label for="mobile-num">Mobile Number</label>
            </div>

            <div class="profile-form-group floating-label" style="display: flex;">
              <input type="password" id="newPassword" placeholder=" ">
              <label for="newPassword">New Password</label>
              <img src="img/eye-close.png" alt="Eye Open/Close" id="reveal-pass">
            </div>
          </div>

          <button class="profile-save-button">Save changes</button>
        </div>
      </div>

      <div class="profile-div-center">
        <div class="profile-types">
          <div class="select-boxes-container">
            <div class="select-boxes">
              <img src="img/logout.png" alt="logout-logo">
              <h3><a href="logout.php">Logout</a></h3>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  document.getElementById("reveal-pass").onclick = function () {
    let newPassword = document.getElementById("newPassword")
    let eyeReveal = document.getElementById("reveal-pass")

    if (newPassword.type === "password") {
      newPassword.type = "text";
      eyeReveal.src = "img/eye-open.png";
    } else {
      newPassword.type = "password";
      eyeReveal.src = "img/eye-close.png";
    }
  }
</script>
</body>
</html>
