<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/authenticated_user.class.php');
    require_once(__DIR__ . '/connection.db.php');
    require_once(__DIR__ . '/class.class.php');

    class Trainer extends AuthenticatedUser {
        // public bool $isActive;
        
        public array $certifications;  // Array of associative arrays with name and description
        public array $specialtizations;  // Array of associative arrays with name and description
        public array $classIds;

        public function __construct(int $id, string $firstName, string $lastName, string $username, string $email, array $certifications, array $specialtizations, array $classIds) {
            parent::__construct($id, $firstName, $lastName, $username, $email, true);
            $this->certifications = $certifications;
            $this->specialtizations = $specialtizations;
            $this->classIds = $classIds;
        }

        static function getTrainerById(PDO $db, int $id) : Trainer {
            
            $stmt = $db->prepare('
                SELECT User.id, User.first_name, User.last_name, User.username, User.email
                    Certification.name AS cert_name, Certification.description AS cert_description,
                    Specialization.name AS spec_name, Specialization.description AS spec_description,
                    Class.id AS class_id
                FROM User
                JOIN Trainer ON User.id = Trainer.trainer_id
                LEFT JOIN TrainerCertification ON Trainer.trainer_id = TrainerCertification.trainer_id
                LEFT JOIN Certification ON TrainerCertification.certification_id = Certification.id
                LEFT JOIN TrainerSpecialization ON Trainer.trainer_id = TrainerSpecialization.trainer_id
                LEFT JOIN Specialization ON TrainerSpecialization.specialization_id = Specialization.id
                LEFT JOIN Class ON Trainer.trainer_id = Class.trainer_id
                WHERE User.id = ? AND User.is_active = 1
            ');
            
            $stmt->execute(array($id));

            $trainer = null;

            while($row = $stmt->fetch()) {
                if ($trainer === null) {
                    $trainer = new Trainer(
                        $row['id'],
                        $row['first_name'],
                        $row['last_name'],
                        $row['username'],
                        $row['email'],
                        [], // certifications
                        [], // specialtizations
                        []  // classIds
                    );
                }

                if($row['cert_name'] !== null) {
                    $cert = [
                        'name' => $row['cert_name'],
                        'description' => $row['cert_description']
                    ];
                    // Only add if not already in array
                    if (!in_array($cert, $trainer->certifications)) {
                        $trainer->certifications[] = $cert;
                    }
                }

                if($row['spec_name'] !== null) {
                    $spec = [
                        'name' => $row['spec_name'],
                        'description' => $row['spec_description']
                    ];
                    // Only add if not already in array
                    if (!in_array($spec, $trainer->specialtizations)) {
                        $trainer->specialtizations[] = $spec;
                    }
                }

                if($row['class_id'] !== null) {
                    // Only add if not already in array
                    if (!in_array($row['class_id'], $trainer->classIds)) {
                        $trainer->classIds[] = $row['class_id'];
                    }
                }
            }
            return $trainer;
        }

        public function getClasses() : array {
            $db = getDatabaseConnection();
            $classes = [];
            foreach ($this->classIds as $classId) {
                $classes[] = GymClass::getClassById($db, $classId);
            }
            return $classes;
        }

        static function getAllTrainers(PDO $db) : array {
            $stmt = $db->prepare('
                SELECT User.id, User.first_name, User.last_name, User.username, User.email,
                    Certification.name AS cert_name, Certification.description AS cert_description,
                    Specialization.name AS spec_name, Specialization.description AS spec_description
                FROM User
                JOIN Trainer ON User.id = Trainer.trainer_id
                LEFT JOIN TrainerCertification ON Trainer.trainer_id = TrainerCertification.trainer_id
                LEFT JOIN Certification ON TrainerCertification.certification_id = Certification.id
                LEFT JOIN TrainerSpecialization ON Trainer.trainer_id = TrainerSpecialization.trainer_id
                LEFT JOIN Specialization ON TrainerSpecialization.specialization_id = Specialization.id
                WHERE User.is_active = 1
            ');

            $stmt->execute();

            $trainers = [];

            while($row = $stmt->fetch()) {
                $id = $row['id'];

                if(!isset($trainers[$id])) {
                    $trainers[$id] = new Trainer(
                        $row['id'],
                        $row['first_name'],
                        $row['last_name'],
                        $row['username'],
                        $row['email'],
                        [], // certifications
                        [], // specialtizations
                        []  // classIds - not needed for listing all trainers
                    );
                }

                if($row['cert_name'] !== null) {
                    $cert = [
                        'name' => $row['cert_name'],
                        'description' => $row['cert_description']
                    ];
                    // Only add if not already in array
                    if (!in_array($cert, $trainers[$id]->certifications)) {
                        $trainers[$id]->certifications[] = $cert;
                    }
                }

                if($row['spec_name'] !== null) {
                    $spec = [
                        'name' => $row['spec_name'],
                        'description' => $row['spec_description']
                    ];
                    // Only add if not already in array
                    if (!in_array($spec, $trainers[$id]->specialtizations)) {
                        $trainers[$id]->specialtizations[] = $spec;
                    }
                }
            }

            return array_values($trainers);
        }
    }
?>