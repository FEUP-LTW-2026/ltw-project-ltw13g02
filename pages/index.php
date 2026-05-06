<?php
  declare(strict_types = 1);

  require_once (__DIR__ . '/../templates/common.php');
  require_once (__DIR__ . '/../templates/index.php');

  // Dummy data for membership plans
  $plans = [
    ['name' => 'Standard', 'price' => '20$', 'features' => ['Full gym access', 'Access to locker rooms', '1 group class per week', 'Basic fitness assessment']],
    ['name' => 'Premium', 'price' => '30$', 'features' => ['Everything in Standard', 'Unlimited group classes', 'Personalized workout plan', 'Nutrition guidance', 'Progress tracking every 4 weeks']],
    ['name' => 'VIP', 'price' => '40$', 'features' => ['Everything in Premium', '2 personal training sessions per month', 'Priority class booking', 'Access to premium recovery area', 'Exclusive VIP support']]
  ];

  drawHeader('Home', ['index.css']);
  
  drawIndexPage($plans);

  drawFooter();
?>