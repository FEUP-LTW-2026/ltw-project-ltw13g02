<?php
    declare(strict_types = 1);

    require_once (__DIR__ . '/../database/connection.db.php');
    require_once (__DIR__ . '/../database/classType.class.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');
    require_once (__DIR__ . '/../templates/common.php');
    require_once (__DIR__ . '/../templates/classes.php');
    require_once(__DIR__ . '/../utils/session.php');
    
    $session = new Session();

    require_once(__DIR__ . '/../utils/protected_file_guard.php');

    $db = getDatabaseConnection();

    $classes = ClassType::getAllClassesTypes($db);

    drawHeader($session, 'Classes', ['classes.css']);

    drawIntro('WHAT WE OFFER', 'Our <b>classes</b>', '40+ weekly sessions across strength, cardio, flexibility and recovery. Something for every level.');

    drawClassesPage($classes);
    
    drawFooter(); 
?>

<script src="../javascript/filterClasses.js" defer></script>