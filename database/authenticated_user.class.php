<?php
    declare(strict_types = 1);

    class AuthenticatedUser {
        protected int $id;
        protected string $firstName;
        protected string $lastName;
        protected string $username;
        protected string $email;
        protected bool $isActive;
        
        public function __construct(int $id, string $firstName, string $lastName, string $username, string $email, bool $isActive) {
            $this->id = $id;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->username = $username;
            $this->email = $email;
            $this->isActive = $isActive;
        }

        public function getId() : int {
            return $this->id;
        }

        public function getEmail() : string {
            return $this->email;
        }


        public function getUsername() : string {
            return $this->username;
        }

        public function setUsername(string $username) : void {
            $this->username = $username;
        }

        public function getFirstName() : string {
            return $this->firstName;
        }

        public function IsActive() : bool {
            return $this->isActive;
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
        
        // saves the current state of the user to the database
        public function save(PDO $db) : void {
            $stmt = $db->prepare('UPDATE User SET first_name = ?, last_name = ?, username = ? WHERE id = ?');
            $stmt->execute([$this->firstName, $this->lastName, $this->username, $this->id]);
        }

        public function setIsActive(bool $isActive) : void {
            $this->isActive = $isActive;
        }

        public static function getUserWithPassword(PDO $db, string $emailUsername, string $password) : ?AuthenticatedUser {

            // try with email
            $stmt = $db->prepare('SELECT * FROM User WHERE email = ?');
            $stmt->execute([$emailUsername]);
            $userData = $stmt->fetch();

            if ($userData && password_verify($password, $userData['password'])) {
                return new AuthenticatedUser(
                    $userData['id'],
                    $userData['first_name'],
                    $userData['last_name'],
                    $userData['username'],
                    $userData['email'],
                    (bool) $userData['is_active']
                );

            } else { // try with username

                $stmt = $db->prepare('SELECT * FROM User WHERE username = ?');
                $stmt->execute([$emailUsername]);
                $userData = $stmt->fetch();

                if ($userData && password_verify($password, $userData['password'])) {
                    return new AuthenticatedUser(
                        $userData['id'],
                        $userData['first_name'],
                        $userData['last_name'],
                        $userData['username'],
                        $userData['email'],
                        (bool) $userData['is_active']
                    );
                }

                return null; // user not found
            }
        }

        public static function createUser(PDO $db, string $firstName, string $lastName, string $email, string $username, string $password) : AuthenticatedUser {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $db->prepare('INSERT INTO User (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$firstName, $lastName, $email, $username, $hashedPassword]);
            $insertedId = (int) $db->lastInsertId();

            return new AuthenticatedUser($insertedId, $firstName, $lastName, $username, $email, true);
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

        public static function getUser(PDO $db, int $id) : ?AuthenticatedUser {
            $stmt = $db->prepare('SELECT * FROM User WHERE id = ?');
            $stmt->execute([$id]);
            $userData = $stmt->fetch();

            if ($userData) {
                return new AuthenticatedUser(
                    $userData['id'],
                    $userData['first_name'],
                    $userData['last_name'],
                    $userData['username'],
                    $userData['email'],
                    (bool) $userData['is_active']
                );
            }

            return null;
        }

        public static function isUsernameAvailable(PDO $db, string $username) : bool {
            $stmt = $db->prepare('SELECT 1 FROM User WHERE username = ?');
            $stmt->execute([$username]);
            return !$stmt->fetch(); // returns true if available
        }

        public static function isEmailAvailable(PDO $db, string $email) : bool {
            $stmt = $db->prepare('SELECT 1 FROM User WHERE email = ?');
            $stmt->execute([$email]);
            return !$stmt->fetch(); // returns true if available
        }

        public static function verifyPassword(PDO $db, int $userId, string $password) : bool {
            $stmt = $db->prepare('SELECT password FROM User WHERE id = ?');
            $stmt->execute([$userId]);
            $userData = $stmt->fetch();

            if ($userData) {
                return password_verify($password, $userData['password']);
            }

            return false; // user not found
        }

        public static function updatePassword(PDO $db, int $userId, string $newPassword) : void {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $db->prepare('UPDATE User SET password = ? WHERE id = ?');
            $stmt->execute([$hashedPassword, $userId]);
        }

        public static function getAllUsers(PDO $db) : array {
            $stmt = $db->query('SELECT * FROM User ORDER BY is_active DESC');
            $usersData = $stmt->fetchAll();
            $users = [];

            foreach ($usersData as $userData) {
                $users[] = new AuthenticatedUser(
                    $userData['id'],
                    $userData['first_name'],
                    $userData['last_name'],
                    $userData['username'],
                    $userData['email'],
                    (bool) $userData['is_active']
                );
            }

            return $users;
        }
    }
?>