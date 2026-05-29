<?php 
  declare(strict_types=1);

  require_once (__DIR__ . '/admin/navbar.php');
  require_once (__DIR__ . '/admin/overview.php');
  require_once (__DIR__ . '/admin/trainer_creation.php');
  require_once (__DIR__ . '/admin/account_management.php');
  require_once (__DIR__ . '/admin/class_catalog.php');
  require_once (__DIR__ . '/admin/equipment_management.php');
  require_once (__DIR__ . '/admin/membership_tiers.php');
?>

<?php function drawAdminPanel(array $context) { ?>
<div class="admin-dashboard container">
  <?php drawNavBar(); ?>    
    <section class="dashboard-content">
      <?php drawOverview($context['db']); ?>
      <?php drawTrainerCreation($context['session']); ?>
      <?php drawAccountManagement($context['users'], $context['session'], $context['roleManager']); ?>
      <?php drawClassCatalog($context['classesByWeekday'], $context['trainers'], $context['session']); ?>
      <?php drawEquipmentManagement($context['equipmentTypes'], $context['equipmentItems'], $context['timeslots'], $context['session'], $context['statusManager']); ?>
      <?php drawMembershipTiers($context['tiers'], $context['session'], $context['perksMap']); ?>
    </section>
  </div>
<?php } ?>