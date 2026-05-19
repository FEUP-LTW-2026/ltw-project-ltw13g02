<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit;
    }
    
    // Create folders if they don't exist
    if (!is_dir('../img')) mkdir('../img');
    if (!is_dir('../img/profiles')) mkdir('../img/profiles');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');

    $db = getDatabaseConnection();
    $user = AuthenticatedUser::getUser($db, $session->getId());

    if (!$user) {
        $session->logout();
        $session->addErrorMessage('User not found. Please log in again.');
        header('Location: ../pages/login.php');
        exit;
    }

    $tempFileName = $_FILES['profile-picture']['tmp_name'];

    // Create an image representation of the original image
    // @ before function is to prevent warning messages
    $newPic = @imagecreatefromjpeg($tempFileName);
    if (!$newPic) $newPic = @imagecreatefrompng($tempFileName);
    if (!$newPic) $newPic = @imagecreatefromgif($tempFileName);

    if (!$newPic) {
        $session->addErrorMessage('Unknown image format. Please upload a JPG, PNG, or GIF file.');
        header('Location: ../pages/editProfile.php');
        exit;
    }

    $width = imagesx($newPic);
    $height = imagesy($newPic);
    $ratio = $width / $height;
    $targetRatio = 250 / 340;

    // Reject if the ratio is way off
    if ($ratio > 1.0 || $ratio < 0.5) {
        $session->addErrorMessage('Please upload an image closer to a vertical aspect ratio.');
        header('Location: ../pages/editProfile.php');
        exit;
    }

    // Determine crop dimensions to preserve target ratio 250x340
    $cropWidth = $width;
    $cropHeight = $height;
    
    if ($ratio > $targetRatio) { // image is too wide, crop width
        $cropWidth = (int)($height * $targetRatio); // crop width to preserve ratio
    } else { // image is too tall, crop height
        $cropHeight = (int)($width / $targetRatio); // crop height to preserve ratio
    }
    
    $cropX = (int)(($width - $cropWidth) / 2);
    $cropY = (int)(($height - $cropHeight) / 2);

    // Create new 250x340 image and crop/resize it
    $finalPic = imagecreatetruecolor(250, 340);
    imagecopyresized($finalPic, $newPic, 0, 0, $cropX, $cropY, 250, 340, $cropWidth, $cropHeight);

    // Save the final image as the user's profile picture (overwriting any existing one)
    // The picture is saved with the user's ID as the filename to associate it with their profile
    imagejpeg($finalPic, '../img/profiles/' . $session->getId() . '.jpg');

    $session->addSuccessMessage('Profile picture updated successfully!');
    header('Location: ../pages/editProfile.php');
?>
