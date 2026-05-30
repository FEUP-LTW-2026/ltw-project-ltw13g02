<?php
    declare(strict_types = 1);

    class AuthenticatedUser {
        private int $id;
        private string $firstName;
        private string $lastName;
        private string $username;
        private string $email;
        private bool $isActive;
        private string $role;
        
        public function __construct(int $id, string $firstName, string $lastName, string $username, string $email, bool $isActive, string $role) {
            $this->id = $id;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->username = $username;
            $this->email = $email;
            $this->isActive = $isActive;
            $this->role = $role;
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
                    (bool) $userData['is_active'],
                    self::getRoleFromDB($db, $userData['id'])
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
                        (bool) $userData['is_active'],
                        self::getRoleFromDB($db, $userData['id'])
                    );
                }

                return null; // user not found
            }
        }

        public static function createUser(PDO $db, string $firstName, string $lastName, string $email, string $username, string $password) : AuthenticatedUser {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $db->prepare('INSERT INTO User (first_name, last_name, email, username, password, is_active, role_change_count) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$firstName, $lastName, $email, $username, $hashedPassword, 1, 0]);
            $insertedId = (int) $db->lastInsertId();

            return new AuthenticatedUser($insertedId, $firstName, $lastName, $username, $email, true, 'User');
        }

        public function isMember(PDO $db) : bool {
            $stmt = $db->prepare('SELECT 1 FROM Member WHERE id = ?');
            $stmt->execute([$this->id]);
            return $stmt->fetch() !== false; // $stmt->fetch() returns false if the query returns no rows
        }

        public function isTrainer(PDO $db) : bool {
            $stmt = $db->prepare('SELECT 1 FROM Trainer WHERE id = ?');
            $stmt->execute([$this->id]);
            return $stmt->fetch() !== false;
        }

        public function isAdmin(PDO $db) : bool {
            $stmt = $db->prepare('SELECT 1 FROM Admin WHERE id = ?');
            $stmt->execute([$this->id]);
            return $stmt->fetch() !== false;
        }

        public static function getUser(PDO $db, int $id) : ?AuthenticatedUser {
            $stmt = $db->prepare('SELECT * FROM User WHERE id = ?');
            $stmt->execute([$id]);
            $userData = $stmt->fetch();
            $role = self::getRoleFromDB($db, $id);

            if ($userData) {
                return new AuthenticatedUser(
                    $userData['id'],
                    $userData['first_name'],
                    $userData['last_name'],
                    $userData['username'],
                    $userData['email'],
                    (bool) $userData['is_active'],
                    $role
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
                $role = self::getRoleFromDB($db, $userData['id']);
                $users[] = new AuthenticatedUser(
                    $userData['id'],
                    $userData['first_name'],
                    $userData['last_name'],
                    $userData['username'],
                    $userData['email'],
                    (bool) $userData['is_active'],
                    $role
                );
            }

            return $users;
        }

        // Used when the user logs in to get their role stored in the session
        private static function getRoleFromDB(PDO $db, int $userId) : string {
            // Single query to get the role of the user
            $stmt = $db->prepare("
                SELECT CASE
                    WHEN Admin.id IS NOT NULL THEN 'Admin'
                    WHEN Trainer.id IS NOT NULL THEN 'Trainer'
                    WHEN Member.id IS NOT NULL THEN 'Member'
                    ELSE 'User'
                END AS role
                FROM User
                LEFT JOIN Admin ON User.id = Admin.id
                LEFT JOIN Trainer ON User.id = Trainer.id
                LEFT JOIN Member  ON User.id = Member.id
                WHERE User.id = ?");
            $stmt->execute([$userId]);
            $result = $stmt->fetch();
            return $result['role'];
        }

        public function getRole() : string {
            return $this->role;
        }

        public static function getUserRoleChangeCount(PDO $db, int $userId): ?int {
            $stmt = $db->prepare('SELECT role_change_count FROM User WHERE id = ?');
            $stmt->execute([$userId]);
            return $stmt->fetchColumn();
        }

        public static function isUserActive(PDO $db, int $userId): bool {
            $stmt = $db->prepare('SELECT is_active FROM User WHERE id = ?');
            $stmt->execute([$userId]);
            return (bool) $stmt->fetchColumn();
        }
    }
?>