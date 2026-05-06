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

      <form method="get">
        <label>
          First Name
          <input type="text" name="first-name" placeholder="user first name">
        </label>

        <label>
          Last Name
          <input type="text" name="last-name" placeholder="user last name">
        </label>

        <label>
          Username
          <input type="text" name="username" placeholder="user username">
        </label>

        <label>
          Password
          <input type="password" name="username" placeholder="Enter new password">
        </label>

        <input class="btn medium blue" type="submit" value="Edit">
      </form>
    </div>
  </div>
<?php } ?>

<?php function drawEditProfilePage() { ?>
  <?php drawProfileEditForm(); ?>
<?php } ?>
