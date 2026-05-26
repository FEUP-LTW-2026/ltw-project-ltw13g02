<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/membership_tier.class.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/role_change_guard.php');

    if (!$session->isLoggedIn() || !$session->isAdmin()) {
        redirectToLogin();
    }

    if (empty($_POST['name']) || empty($_POST['price']) || empty($_POST['booking_window_days'])) {
        $session->addErrorMessage('Invalid request. Please fill in all required fields.');
        redirectToPreviousPage();
    }

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }

    $name = trim((string)$_POST['name']);
    if(!validateText($name)) {
        $session->addErrorMessage('Invalid name. Please use only letters and spaces.');
        redirectToPreviousPage();
    }

    $price = (float)$_POST['price'];
    if($price < 0) {
        $session->addErrorMessage('Invalid price. Please enter a non-negative value.');
        redirectToPreviousPage();
    }

    $bookingWindowDays = (int)$_POST['booking_window_days'];
    if($bookingWindowDays < 0) {
        $session->addErrorMessage('Invalid booking window. Please enter a non-negative integer.');
        redirectToPreviousPage();
    }

    $description = '';
    if (isset($_POST['description'])) {
        if (is_array($_POST['description'])) {
            $parts = [];
            foreach ($_POST['description'] as $part) {
                $trimmedPart = trim((string)$part);
                if ($trimmedPart !== '') {
                    // Validate each description part: max 200 chars, no HTML tags
                    if (strlen($trimmedPart) > 200) {
                        $session->addErrorMessage('Each perk must be 200 characters or less.');
                        redirectToPreviousPage();
                        exit;
                    }
                    if (strip_tags($trimmedPart) !== $trimmedPart) {
                        $session->addErrorMessage('Perks cannot contain HTML tags.');
                        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
                        exit;
                    }
                    $parts[] = $trimmedPart;
                }
            }
            $description = implode(';', $parts);
        } else {
            $description = trim((string)$_POST['description']);
        }
    }

    try {
        $db = getDatabaseConnection();
        MembershipTier::create($db, $name, $description, $price, $bookingWindowDays);

        $session->addSuccessMessage('Membership tier created successfully.');
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
        exit;
    } catch (PDOException $e) {
        $session->addErrorMessage('Database error occurred while creating the tier.');
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
        exit;
    } catch (Exception $e) {
        $session->addErrorMessage('An unexpected error occurred while creating the tier.');
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/admin.php#membership-tiers'));
        exit;
    }
?>