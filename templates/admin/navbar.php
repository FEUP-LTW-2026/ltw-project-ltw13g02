<?php
declare(strict_types=1);

function drawNavBar() { ?>
  <aside>
    <header>
      <h3>Admin Panel</h3>
      <p>Dashboard Management</p>
    </header>

    <nav class="admin-nav">
      <ul>
        <li><a href="#overview" class="active" data-section="overview">Gym Overview</a></li>
        <li><a href="#account-management" data-section="accounts">Manage Accounts</a></li>
        <li><a href="#class-catalog" data-section="catalog">Class Catalog</a></li>
        <li><a href="#equipment-management" data-section="equipment">Manage Equipment</a></li>
        <li><a href="#membership-tiers" data-section="tiers">Membership Tiers</a></li>
      </ul>
    </nav>
  </aside>
<?php }