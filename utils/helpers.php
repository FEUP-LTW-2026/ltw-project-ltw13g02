<?php 
    declare(strict_types=1);

    function getProfileImageUrl(?int $userId): string {
        return file_exists('../img/profiles/' . $userId . '.jpg')
        ? '../img/profiles/' . $userId . '.jpg'
        : '../img/profiles/default.jpg';
    }

    function saveClassImage(int $classTypeId): void {
        // No file uploaded — skip silently (image is optional)
        if (!isset($_FILES['class-image']) || $_FILES['class-image']['error'] === UPLOAD_ERR_NO_FILE) return;

        if ($_FILES['class-image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Image upload failed with error code: ' . $_FILES['class-image']['error']);
        }

        $classesDir = __DIR__ . '/../img/classes';
        if (!is_dir($classesDir) && !mkdir($classesDir, 0777, true)) {
            throw new Exception('Could not create image directory.');
        }

        $tempFileName = $_FILES['class-image']['tmp_name'];

        $img = @imagecreatefromstring(file_get_contents($tempFileName));
        if (!$img) throw new Exception('Unsupported image format. Please upload a JPG, PNG, GIF, or WebP.');

        $width  = imagesx($img);
        $height = imagesy($img);

        // Target ratio: 800x534 (landscape ~3:2)
        $targetRatio = 800 / 534;
        $srcRatio    = $width / $height;

        if ($srcRatio > $targetRatio) {
            $cropW = (int)($height * $targetRatio);
            $cropH = $height;
        } else {
            $cropW = $width;
            $cropH = (int)($width / $targetRatio);
        }

        $cropX = (int)(($width - $cropW) / 2);
        $cropY = (int)(($height - $cropH) / 2);

        $final = imagecreatetruecolor(800, 534);
        imagecopyresized($final, $img, 0, 0, $cropX, $cropY, 800, 534, $cropW, $cropH);

        $dest = $classesDir . '/' . $classTypeId . '.jpg';
        if (!imagejpeg($final, $dest)) {
            throw new Exception('Could not write image to: ' . $dest);
        }

        imagedestroy($img);
        imagedestroy($final);
    }

    function redirectToPreviousPage() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    function redirectToHomePage() {
        header('Location: /pages/index.php');
        exit;
    }

    function redirectToLogin() {
        header('Location: /pages/login.php');
        exit;
    }

    function validateEmail(string $email) : bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    function validateUsername(string $username) : bool {
        return preg_match('/^[a-zA-Z0-9_]+$/', $username) === 1;
    }

    function validatePassword(string $password) : bool {
        // Password must be at least 8 characters, contain at least one uppercase letter, one lowercase letter, one digit, and one special character
        return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/', $password) === 1;
    }

    function validateText(string $text) : bool {
        return preg_match('/^[a-zA-Z]+$/', $text) === 1;
    }

    function validateTextBox(string $text): bool {
        return preg_match('/^[a-zA-Z0-9\s.,!?-]+$/', $text) && strlen($text) <= 500;
    }

    function validateRequiredFields(array $fields): bool {
        foreach ($fields as $field) {
            if (!isset($_POST[$field]) || $_POST[$field] === '') {
                return false;
            }
        }
        return true;
    }
?>