<?php
  declare(strict_types=1);
  require_once (__DIR__ . '/../../database/admin-dash-queries.class.php');
?>


<?php function drawOverview(PDO $db) { ?>
  <article class="dashboard-section active" id="overview">
    <header>
      <div class="section-header">
        <h2>Gym Overview</h2>
        <p>Welcome to the admin dashboard. Here you have a bird's-eye view of your gym.</p>
      </div>
    </header>

    <ul class="stats-grid">
      <li>
        <article class="stat-card">
          <h4>Monthly Revenue By Tier</h4>
          <?php foreach (QueriesForAdminDashboard::getMonthlyRevenueByTier($db) as $row) { ?>
            <p><?= htmlspecialchars($row['tier']) ?>: <span class="stat-number"><?= number_format($row['monthly_revenue'], 2) ?>€</span></p>
          <?php } ?>
          <p>Total Monthly Revenue: <span class="stat-number"><?= number_format(QueriesForAdminDashboard::getTotalMonthlyRevenue($db), 2) ?>€</span></p>
        </article>
      </li>
      <li>
        <article class="stat-card">
          <h4>Active Memberships By Tier</h4>
          <?php foreach (QueriesForAdminDashboard::getActiveMembershipsByTier($db) as $row) { ?>
            <p><?= htmlspecialchars($row['tier']) ?>: <span class="stat-number"><?= (int)$row['active_memberships'] ?></span></p>
          <?php } ?>
        </article>
      </li>
      <li>
        <article class="stat-card">
          <h4>Equipments in Maintenance</h4>
          <?php foreach (QueriesForAdminDashboard::equipmentsInMaintenance($db) as $row) { ?>
            <p><span class="stat-number"><?= htmlspecialchars($row['equipment_type_name']) ?></span> - <?= htmlspecialchars($row['equipment_name']) ?></p>
          <?php } ?>
        </article>
      </li>
      <li>
        <article class="stat-card">
          <h4>Most Booked Equipment This Month</h4>
          <?php foreach (QueriesForAdminDashboard::getMostBookedEquipmentThisMonth($db) as $row) { ?>
            <p><span class="stat-number"><?= htmlspecialchars($row['equipment_type_name']) ?></span> <?= htmlspecialchars($row['equipment_name']) ?> - <?= (int)$row['bookings'] ?> bookings</p>
          <?php } ?>
        </article>
      </li>
      <li>
        <article class="stat-card">
          <h4>Most Attended Classes This Month</h4>
          <?php foreach (QueriesForAdminDashboard::getMostAttendedClassesThisMonth($db) as $row) { ?>
            <p><span class="stat-number"><?= htmlspecialchars($row['class_name']) ?></span> - <?= $row['trainer_name'] ?> - <?= (int)$row['enrollments'] ?> attended</p>
          <?php } ?>
        </article>
      </li>
      <li>
        <article>
          <h4>Best Reviewed Classes</h4>
          <?php foreach (QueriesForAdminDashboard::getBestReviewedClasses($db) as $row) { ?>
            <p>
              <span class="stat-number"><?= htmlspecialchars($row['class_name']) ?></span>
               - 
               <?= number_format($row['average_rating'], 2) ?> stars
               (<?= (int)$row['review_count'] === 1 ? (int)$row['review_count'] . ' review' : (int)$row['review_count'] . ' reviews' ?>)
            </p>
          <?php } ?>
        </article>
      </li>
    </ul>
  </article>
<?php } ?>