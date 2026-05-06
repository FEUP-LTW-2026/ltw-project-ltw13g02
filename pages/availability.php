<?php
  declare(strict_types=1);

  require_once (__DIR__ . '/../templates/common.php');
  require_once (__DIR__ . '/../templates/availability.php');

  $equipement = [
    ['name' => 'Treadmill', 'status' => 'Available', 'count' => '4/6 free'],
    ['name' => 'Bike', 'status' => 'Unavailable', 'count' => '0/4 free'],
    ['name' => 'Bench', 'status' => 'Limited', 'count' => '2/6 free'],
    ['name' => 'Functional Zone', 'status' => 'Available', 'count' => '4/6 free']
  ];

  $treadmills = [
    ['id' => '01', 'status' => 'Available'],
    ['id' => '02', 'status' => 'Available'],
    ['id' => '03', 'status' => 'Available']
  ];

  drawHeader('Availability', ['availability.css','classes.css']);
  
  drawIntro('LIVE UPDATES', 'Check <b>availability</b>', 'Find out which equipment is free or already reserved.');

  drawAvailabilityPage($equipement, $treadmills);
  
  drawFooter(); 
?>
