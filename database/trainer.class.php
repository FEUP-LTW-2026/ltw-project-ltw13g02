<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/authenticated_user.class.php');
    require_once(__DIR__ . '/connection.db.php');
    require_once(__DIR__ . '/classType.class.php');

    class Trainer extends AuthenticatedUser {

        public string $bio;
        public array $certifications;  // Array of associative arrays with name and description
        public array $specialitizations;  // Array of associative arrays with name and description
        public array $classes;

        public function __construct(int $id, string $firstName, string $lastName, string $username, string $email, string $bio, array $certifications = [], array $specialitizations = [], array $classes = []) {
            parent::__construct($id, $firstName, $lastName, $username, $email, true, 'Trainer');
            $this->bio = $bio;
            $this->certifications = $certifications;
            $this->specialitizations = $specialitizations;
            $this->classes = $classes;
        }

        static function getAllTrainers(PDO $db) : array {
            // Query 1: Base Trainer info
            $stmt = $db->prepare('
                SELECT User.id, User.first_name, User.last_name, User.username, User.email, Trainer.bio
                FROM User
                JOIN Trainer ON User.id = Trainer.id
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
                    $trainers[$id]->specializations[] = [
                        'name' => $row['name'],
                        'description' => $row['description'] ?? '',
                        'logo' => $row['logo'] ?? ''
                    ];
                }
            }

            return array_values($trainers);
        }

        static function getTrainerById(PDO $db, int $trainerId) : Trainer {
            // Query 1: Base Trainer info
            $stmt = $db->prepare('
                SELECT User.id, User.first_name, User.last_name, User.username, User.email, Trainer.bio
                FROM User
                JOIN Trainer ON User.id = Trainer.id
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
            
            // Query 2: Certifications
            $stmt = $db->prepare('
                SELECT Certification.name
                FROM TrainerCertification
                JOIN Certification ON TrainerCertification.certification_id = Certification.id
                WHERE TrainerCertification.trainer_id = ?
            ');

            $stmt->execute([$trainerId]);
        
            foreach ($stmt->fetchAll() as $row) {
                $trainer->certifications[] = [
                    'name' => $row['name']
                ];
            }

            // Query 3: Specializations
            $stmt = $db->prepare('
                SELECT Specialization.name, Specialization.description, Specialization.logo
                FROM TrainerSpecialization
                JOIN Specialization ON TrainerSpecialization.specialization_id = Specialization.id
                WHERE TrainerSpecialization.trainer_id = ?
            ');

            $stmt->execute([$trainerId]);
            
            foreach ($stmt->fetchAll() as $row) {
                $trainer->specialitizations[] = [
                    'name' => $row['name'],
                    'description' => $row['description'] ?? '',
                    'logo' => $row['logo'] ?? ''
                ];
            }

            // Query 4: Classes
            $stmt = $db->prepare('
                SELECT ClassType.id
                FROM ClassType
                WHERE ClassType.trainer_id = ?
            ');

            $stmt->execute([$trainerId]);
            foreach ($stmt->fetchAll() as $row) {
                $trainer->classes[] = ClassType::getClassTypeById($db, $row['id']);
            }

            return $trainer;
        }

        static function getAllTrainersBrief(PDO $db) : array {
            $stmt = $db->prepare('
                SELECT User.id, User.first_name, User.last_name
                FROM User
                JOIN Trainer ON User.id = Trainer.id
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
    }
?>