<?php 
  declare(strict_types=1);
?>

<?php function drawProfileEditForm() { ?>
  <div class="intro">
    <div id="profile">
      <label for="profile-pic" class="profile-pic-label">
        <img 
          src="https://picsum.photos/250/340" 
          alt="profile photo" 
          class="profile-image"
          style="cursor:pointer;"
        >
        <span class="profile-image-prompt">Click on the image to change it</span>
      </label>

      <input 
        type="file" 
        id="profile-pic" 
        accept="image/*" 
        hidden
      >

      <form method="post" action="../actions/action_editProfile.php">
        <label>
          First Name
          <input type="text" name="first-name" placeholder="New first name">
        </label>

        <label>
          Last Name
          <input type="text" name="last-name" placeholder="New last name">
        </label>

        <label>
          Username
          <input type="text" name="username" placeholder="New username">
        </label>

        <button class="btn medium blue" type="submit">Save changes</button>
      </form>


      <div class="change-password">
        <p class="detail">Change password</p>

        <form method="post" action="../actions/action_changePassword.php">
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
<?php } ?>

<?php function drawEditProfilePage() {
  drawProfileEditForm();
} ?>
