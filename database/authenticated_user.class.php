<?php
    declare(strict_types = 1);

    class AuthenticatedUser {
        private int $id;
        private string $firstName;
        private string $lastName;
        private string $email;

        public function __construct(int $id, string $firstName, string $lastName, string $email) {
            $this->id = $id;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->email = $email;
        }

        public function getId() : int {
            return $this->id;
        }

        public function getEmail() : string {
            return $this->email;
        }

        public function getFirstName() : string {
            return $this->firstName;
        }

        public function setFirstName(string $firstName) : void {
            $this->firstName = $firstName;
        }

        public function getLastName() : string {
            return $this->lastName;
        }

        public function setLastName(string $lastName) : void {
            $this->lastName = $lastName;
        }

        public function getFullName() : string {
            return $this->firstName . ' ' . $this->lastName;
        }

        public static function getUserWithPassword(PDO $db, string $email, string $password) : ?AuthenticatedUser {
            $stmt = $db->prepare('SELECT * FROM User WHERE email = ?');
            $stmt->execute([$email]);
            $userData = $stmt->fetch();

            if ($userData && password_verify($password, $userData['password'])) {
                return new AuthenticatedUser(
                    $userData['id'],
                    $userData['first_name'],
                    $userData['last_name'],
                    $userData['email']
                );

            } else {
                return null;
            }
        }

        public static function createUser(PDO $db, string $firstName, string $lastName, string $email, string $password) : AuthenticatedUser {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $username = strtolower($firstName . $lastName); // for now
            
            $stmt = $db->prepare('INSERT INTO User (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$firstName, $lastName, $email, $username, $hashedPassword]);
            $insertedId = (int) $db->lastInsertId();

            return new AuthenticatedUser($insertedId, $firstName, $lastName, $email);
        }

        public function isMember(PDO $db) : bool {
            $stmt = $db->prepare('SELECT 1 FROM Member WHERE id = ?');
            $stmt->execute([$this->id]);
            return $stmt->fetch() !== false; // $stmt->fetch() returns false if the query returns no rows
        }

        public function isTrainer(PDO $db) : bool {
            $stmt = $db->prepare('SELECT 1 FROM Trainer WHERE trainer_id = ?');
            $stmt->execute([$this->id]);
            return $stmt->fetch() !== false;
        }

        public function isAdmin(PDO $db) : bool {
            $stmt = $db->prepare('SELECT 1 FROM Admin WHERE admin_id = ?');
            $stmt->execute([$this->id]);
            return $stmt->fetch() !== false;
        }

        public function getUser(PDO $db) : ?AuthenticatedUser {
            $stmt = $db->prepare('SELECT * FROM User WHERE id = ?');
            $stmt->execute([$this->id]);
            $userData = $stmt->fetch();

            if ($userData) {
                return new AuthenticatedUser(
                    $userData['id'],
                    $userData['first_name'],
                    $userData['last_name'],
                    $userData['email']
                );
            }

            return null;
        }
    }
?>