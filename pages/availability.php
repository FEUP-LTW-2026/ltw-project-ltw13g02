<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once (__DIR__ . '/../templates/common.php');
    require_once (__DIR__ . '/../templates/availability.php');
    require_once (__DIR__ . '/../database/connection.db.php');
    require_once (__DIR__ . '/../database/equipment_type.class.php');
    require_once (__DIR__ . '/../database/equipment_type_map.class.php');
    require_once (__DIR__ . '/../database/timeslot.class.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/protected_file_guard.php');

    if (!$session->isLoggedIn()) {
        header('Location: login.php');
        exit;

    } else if (!$session->isMember()) {
        header('Location: index.php');
        exit;

    } else {

        $db = getDatabaseConnection();

        $equipmentTypes = EquipmentType::getAllEquipmentTypes($db);

        $equipmentTypeMap = new EquipmentTypeMap(EquipmentTypeMap::getMapEquipmentTypeEquipments($db));

        $timeslots = Timeslot::getAllTimeslots($db);

        drawHeader($session, 'Availability', ['availability.css','classes.css']);

        drawIntro('LIVE UPDATES', 'Check <b>availability</b>', 'Find out which equipment is free or already reserved.');

        drawAvailabilityPage($equipmentTypes, $equipmentTypeMap, $timeslots, $db, $session);

        drawFooter();
    }
?>

<script src="../javascript/toggleEquipmentData.js" defer></script>
