<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/authenticated_user.class.php');
    require_once(__DIR__ . '/connection.db.php');
    require_once(__DIR__ . '/classType.class.php');
    require_once(__DIR__ . '/gymClass.class.php');

    class Trainer extends AuthenticatedUser {

        public string $bio;
        public array $certifications;  // Array of associative arrays with name and description
        public array $specialitizations;  // Array of associative arrays with name and description
        public array $classes;
        public array $scheduledClasses;

        public function __construct(int $id, string $firstName, string $lastName, string $username, string $email, string $bio, array $certifications = [], array $specialitizations = [], array $classes = [], array $scheduledClasses = []) {
            parent::__construct($id, $firstName, $lastName, $username, $email, true);
            $this->bio = $bio;
            $this->certifications = $certifications;
            $this->specialitizations = $specialitizations;
            $this->classes = $classes;
            $this->scheduledClasses = $scheduledClasses;
        }

        public static function updateBiography(PDO $db, int $trainerId, string $newBio) : void {
            $stmt = $db->prepare('UPDATE Trainer SET bio = ? WHERE trainer_id = ?');
            $stmt->execute([$newBio, $trainerId]);
        }

        static function getAllTrainers(PDO $db) : array {
            // Query 1: Base Trainer info
            $stmt = $db->prepare('
                SELECT User.id, User.first_name, User.last_name, User.username, User.email, Trainer.bio
                FROM User
                JOIN Trainer ON User.id = Trainer.trainer_id
                WHERE User.is_active = 1
            ');

            $stmt->execute();
            $row = $stmt->fetchAll();

            if(empty($row)) return [];

            $trainers = [];
            foreach($row as $trainer) {
                $trainers[$trainer['id']] = new Trainer(
                    $trainer['id'],
                    $trainer['first_name'],
                    $trainer['last_name'],
                    $trainer['username'],
                    $trainer['email'],
                    $trainer['bio'],
                );
            }
            
            // Query 2: Certifications
            $stmt = $db->prepare('
                SELECT TrainerCertification.trainer_id, Certification.name
                FROM TrainerCertification
                JOIN Certification ON TrainerCertification.certification_id = Certification.id
            ');

            $stmt->execute();
        
            foreach ($stmt->fetchAll() as $row) {
                $id = $row['trainer_id'];
                if (isset($trainers[$id])) {
                    $trainers[$id]->certifications[] = [
                        'name' => $row['name']
                    ];
                }
            }

            // Query 3: Specializations
            $stmt = $db->prepare('
                SELECT TrainerSpecialization.trainer_id, Specialization.name, Specialization.description, Specialization.logo
                FROM TrainerSpecialization
                JOIN Specialization ON TrainerSpecialization.specialization_id = Specialization.id
            ');

            $stmt->execute();
            
            foreach ($stmt->fetchAll() as $row) {
                $id = $row['trainer_id'];
                if (isset($trainers[$id])) {
                    $trainers[$id]->specialitizations[] = [
                        'name' => $row['name'],
                        'description' => $row['description'] ?? '',
                        'logo' => $row['logo'] ?? ''
                    ];
                }
            }

            return array_values($trainers);
        }

        static function getTrainerById(PDO $db, int $trainerId) : ?Trainer {
            // Base Trainer info
            $stmt = $db->prepare('
                SELECT User.id, User.first_name, User.last_name, User.username, User.email, Trainer.bio
                FROM User
                JOIN Trainer ON User.id = Trainer.trainer_id
                WHERE User.is_active = 1 AND User.id = ?
            ');

            $stmt->execute([$trainerId]);
            $row = $stmt->fetch();

            if(empty($row)) return null;

            $trainer = new Trainer(
                $row['id'],
                $row['first_name'],
                $row['last_name'],
                $row['username'],
                $row['email'],
                $row['bio']
            );
            
            // Certifications
            $stmt = $db->prepare('
                SELECT Certification.id, Certification.name
                FROM TrainerCertification
                JOIN Certification ON TrainerCertification.certification_id = Certification.id
                WHERE TrainerCertification.trainer_id = ?
            ');

            $stmt->execute([$trainerId]);
        
            foreach ($stmt->fetchAll() as $row) {
                $trainer->certifications[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
            }

            // Specializations
            $stmt = $db->prepare('
                SELECT Specialization.id, Specialization.name, Specialization.description, Specialization.logo
                FROM TrainerSpecialization
                JOIN Specialization ON TrainerSpecialization.specialization_id = Specialization.id
                WHERE TrainerSpecialization.trainer_id = ?
            ');

            $stmt->execute([$trainerId]);
            
            foreach ($stmt->fetchAll() as $row) {
                $trainer->specialitizations[] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'description' => $row['description'] ?? '',
                    'logo' => $row['logo'] ?? ''
                ];
            }

            // Classes
            $stmt = $db->prepare('
                SELECT ClassType.id
                FROM ClassType
                WHERE ClassType.trainer_id = ?
            ');

            $stmt->execute([$trainerId]);
            foreach ($stmt->fetchAll() as $row) {
                $trainer->classes[] = ClassType::getClassTypeById($db, $row['id']);
            }

            // Scheduled classes
            $stmt = $db->prepare('
                SELECT Class.id
                FROM Class
                JOIN ClassType ON Class.class_type_id = ClassType.id
                WHERE ClassType.trainer_id = ? AND Class.status = "scheduled"
            ');

            $stmt->execute([$trainerId]);
            foreach ($stmt->fetchAll() as $row) {
                $trainer->scheduledClasses[] = GymClass::getClassById($db, $row['id']);
            }

            return $trainer;
        }

        static function getAllTrainersBrief(PDO $db) : array {
            $stmt = $db->prepare('
                SELECT User.id, User.first_name, User.last_name
                FROM User
                JOIN Trainer ON User.id = Trainer.trainer_id
                WHERE User.is_active = 1
            ');

            $stmt->execute();
            $trainers = [];
            foreach ($stmt->fetchAll() as $trainer) {
                $trainers[] = new Trainer(
                    $trainer['id'],
                    $trainer['first_name'],
                    $trainer['last_name'],
                    '', // empty username
                    '', // empty email
                    ''  // empty bio
                );
            }
            return $trainers;
        }

        public static function getAllSpecializations(PDO $db) : array {
            $stmt = $db->prepare('SELECT id, name, description, logo FROM Specialization');
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public static function getAllCertifications(PDO $db) : array {
            $stmt = $db->prepare('SELECT id, name FROM Certification');
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public static function addSpecialization(PDO $db, int $trainerId, int $specializationId) : void {
            $stmt = $db->prepare('INSERT INTO TrainerSpecialization (trainer_id, specialization_id) VALUES (?, ?)');
            $stmt->execute([$trainerId, $specializationId]);
        }

        public static function removeSpecialization(PDO $db, int $trainerId, int $specializationId) : void {
            $stmt = $db->prepare('DELETE FROM TrainerSpecialization WHERE trainer_id = ? AND specialization_id = ?');
            $stmt->execute([$trainerId, $specializationId]);
        }

        public static function addCertification(PDO $db, int $trainerId, int $certificationId) : void {
            $stmt = $db->prepare('INSERT INTO TrainerCertification (trainer_id, certification_id) VALUES (?, ?)');
            $stmt->execute([$trainerId, $certificationId]);
        }

        public static function removeCertification(PDO $db, int $trainerId, int $certificationId) : void {
            $stmt = $db->prepare('DELETE FROM TrainerCertification WHERE trainer_id = ? AND certification_id = ?');
            $stmt->execute([$trainerId, $certificationId]);
        }
    }
?>