<?php 
  declare(strict_types=1);

  function getProfileImageUrl(?int $userId): string {
    return file_exists('../img/profiles/' . $userId . '.jpg')
      ? '../img/profiles/' . $userId . '.jpg'
      : '../img/profiles/default.jpg';
  }
?>

<?php function drawProfileEditForm(int $userId, string $firstName, string $lastName, string $username, Session $session) { 
  $photoUrl = getProfileImageUrl($userId);
  ?>
  <div class="intro">
    <div id="profile">
      
      <div class="profile-picture">
        <img src="<?= htmlspecialchars($photoUrl) ?>" alt="profile photo" class="profile-image" >

        <form method="post" action="../actions/action_uploadProfilePicture.php" enctype="multipart/form-data" id="upload-form">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
          <input type="file" name="profile-picture" id="image-input">
          <button type="button" class="btn medium blue" id="upload-btn">Change Profile Picture</button>
        </form>
      </div>

      <form method="post" action="../actions/action_editProfile.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
        <label>
          First Name
          <input type="text" name="first-name" value="<?= htmlspecialchars($firstName) ?>" placeholder="New first name">
        </label>

        <label>
          Last Name
          <input type="text" name="last-name" value="<?= htmlspecialchars($lastName) ?>" placeholder="New last name">
        </label>

        <label>
          Username
          <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" placeholder="New username">
        </label>

        <button class="btn medium blue" type="submit">Save changes</button>
      </form>


      <div class="change-password">
        <p class="detail">Change password</p>

        <form method="post" action="../actions/action_changePassword.php">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
          <label>
            Current password
            <input type="password" name="current-password" placeholder="Enter current password" required>
          </label>

          <label>
            New password
            <input type="password" name="new-password" placeholder="Enter new password" required>
          </label>

          <label>
            Confirm new password
            <input type="password" name="confirm-password" placeholder="Confirm new password" required>
          </label>
          <button class="btn medium blue" type="submit">Change Password</button>
        </form>
      </div>
    </div>
  </div>

  <script src="../javascript/editProfile.js"></script>
<?php } ?>

<?php function drawEditProfilePage(int $userId, string $firstName, string $lastName, string $username, Session $session) {
  drawProfileEditForm($userId, $firstName, $lastName, $username, $session);
} ?>
