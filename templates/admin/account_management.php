<?php
    declare(strict_types=1);
?>

<?php function drawAccountManagement(array $users, Session $session, RoleManager $roleManager) { ?>
  <article class="dashboard-section" id="account-management">
    <header>
      <div class="section-header">
        <h2>Manage Accounts</h2>
        <p>Create, update, deactivate accounts, or elevate users to admin.</p>
      </div>
      <button class="btn transparent-dark medium" id="add-user-btn">+ Add New User</button>
    </header>
    <!-- Search bar for filtering users, by username -->
    <input type="text" id="search-bar" placeholder="Search users by username...">
    <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user) { ?>
            <?php if ($user->getId() === $session->getId()) continue;?> <!-- Skip current user - admin -->
              <tr>
                <td><?= htmlspecialchars($user->getUsername()) ?></td>
                <td><?= htmlspecialchars($user->getEmail()) ?></td>
                <td>
                  <div class="role-update-container">
                      <span><?= htmlspecialchars($user->getRole()) ?></span>
                      <form action="../actions/action_changeRole.php" method="POST" id="role-form-<?= (int) $user->getId() ?>" class="role-update-form">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                        <input type="hidden" name="user_id" value="<?= (int) $user->getId() ?>">
                        <input type="hidden" name="user_name" value="<?= htmlspecialchars($user->getUsername()) ?>">
                        <input type="hidden" name="old_role_table" value="<?= htmlspecialchars($user->getRole()) ?>">
                        <select name="new_role_table">
                          <option value="" disabled selected>Change Role</option>
                          <?php 
                            $roles = $roleManager->getAvailableRoles($user->getRole());
                            foreach ($roles as $role) { ?>
                            <option value="<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></option>
                          <?php } ?>
                        </select>
                      </form>
                  </div>
                </td>
                <td><span class="status <?= $user->IsActive() ? 'active' : 'inactive' ?>"><?= $user->IsActive() ? 'Active' : 'Inactive' ?></span></td>
                <td class="actions">
                  <button class="btn white" type="submit" form="role-form-<?= (int) $user->getId() ?>">Submit</button>
                  <?php if (!$user->IsActive()) { ?>
                    <form action="../actions/action_toggle_user_status.php" method="POST">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                      <input type="hidden" name="user_id" value="<?= (int) $user->getId() ?>">
                      <input type="hidden" name="is_active" value="<?= !$user->IsActive() ?>">
                      <button class="btn available">Activate</button>
                    </form>
                  <?php } else { ?>
                    <form action="../actions/action_toggle_user_status.php" method="POST">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                      <input type="hidden" name="user_id" value="<?= (int) $user->getId() ?>">
                      <input type="hidden" name="is_active" value="<?= !$user->IsActive() ?>">
                      <button class="btn warning">Deactivate</button>
                    </form>
                  <?php } ?>
                </td>
              </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <dialog id="dialog-add-user">
      <div class="dialog-content">
        <h3>Add New User</h3>
        <form action="../actions/action_create_user.php" method="POST">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

          <label>First Name
            <input type="text" name="first-name" placeholder="First Name" required>
          </label>

          <label>Last Name
            <input type="text" name="last-name" placeholder="Last Name" required>
          </label>

          <label>Username
            <input type="text" name="username" placeholder="Username" required>
          </label>

          <label>Email
            <input type="email" name="email" placeholder="Email" required>
          </label>

          <label>Password
            <input type="password" name="password" placeholder="Password" required>
          </label>

          <label>Confirm Password
            <input type="password" name="confirm-password" placeholder="Confirm Password" required>
          </label>

          <div class="dialog-actions">
            <button type="submit" class="btn medium blue">Create User</button>
            <button type="button" class="btn medium warning btn-close-dialog">Cancel</button>
          </div>
        </form>
      </div>
    </dialog>
  </article>
<?php } ?>