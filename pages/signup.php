<?php
    declare(strict_types = 1);

    require_once (__DIR__ . '/../templates/common.php');
    
    drawHeader('Sign Up');
?>
    <div class="intro">
      <section id="auth" class="container">
        <header>
          <h1><b>Sign Up</b></h1>
          <h2>Become a RAT.</h2>
        </header>
        
        <form class="form-signup" method="get">
          <label>
            First name
            <input type="text" name="first-name" placeholder="Enter first name" required>
          </label>

          <label>
            Last name
            <input type="text" name="last-name" placeholder="Enter last name" required>
          </label>

          <label>
            Email
            <input type="text" name="email" placeholder="Enter email" required>
          </label>

          <label>
            Password
            <input type="password" name="password" placeholder="Enter password" required>
          </label>

          <label>
            Confirm password
            <input type="password" name="confirm-password" placeholder="Enter password" required>
          </label>
          
          <input class="btn large blue" type="submit" value="Register">
          <p>Already have an account? Login <a href="../pages/login.php">here</a>.</p>

        </form>
      </section>
    </div>
  
<?php drawFooter(); ?>
