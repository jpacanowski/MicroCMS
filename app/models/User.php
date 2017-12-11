<?php

class User {

    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function login($username, $password) {

        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        $hashed_password = $row->password;

        if(password_verify($password, $hashed_password)) {
            return $row;
        }
        else {
            return false;
        }
    }

    public function register($data) {

        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $this->db->query('
            INSERT INTO users (username, firstname, lastname, email, password, created_at, updated_at)
            VALUES (:username, :firstname, :lastname, :email, :password, NOW(), NOW())
        ');

        $this->db->bind(':username', $data['username']);
        $this->db->bind(':firstname', $data['firstname']);
        $this->db->bind(':lastname', $data['lastname']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        if($this->db->execute()) {
            return true;
        }
        else {
            return false;
        }
    }

    public function findUserByEmail($username) {
        $this->db->query('  SELECT * FROM users
                            WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        if($this->db->rowCount() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getUser($id) {
        $this->db->query('  SELECT * FROM users
                            WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getUsers() {
        $this->db->query('SELECT * FROM users');
        return $this->db->resultSet();
    }

    public function getUsersNumber() {
        $this->db->query('SELECT * FROM users');
        $this->db->resultSet();
        return $this->db->rowCount();
    }

    public function addUser($user) {
        
        $this->db->query('  INSERT INTO users SET
                            username = :username,
                            firstname = :firstname,
                            lastname = :lastname,
                            email = :email,
                            password = :password,
                            role = :role,
                            created_at = NOW(),
                            updated_at = NOW()
        ');

        $this->db->bind(':username',    $user['username']);
        $this->db->bind(':firstname',   $user['firstname']);
        $this->db->bind(':lastname',    $user['lastname']);
        $this->db->bind(':email',       $user['email']);
        $this->db->bind(':password',    $user['password']);
        $this->db->bind(':role',        $user['role']);

        return $this->db->execute();
    }

    public function updateUserProfile($data) {

        $this->db->query('  UPDATE
                                users
                            SET
                                username = :username,
                                firstname = :firstname,
                                lastname = :lastname,
                                email = :email,
                                updated_at = NOW()
                            WHERE
                                id = :id
        ');

        $this->db->bind(':username', $data['username']);
        $this->db->bind(':firstname', $data['firstname']);
        $this->db->bind(':lastname', $data['lastname']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':id', $data['user_id']);

        return $this->db->execute() ? true : false;
    }

    public function updateUser($data) {
        
        $this->db->query('  UPDATE
                                users
                            SET
                                username = :username,
                                firstname = :firstname,
                                lastname = :lastname,
                                email = :email,
                                role = :role,
                                updated_at = NOW()
                            WHERE
                                id = :id
        ');

        $this->db->bind(':username', $data['username']);
        $this->db->bind(':firstname', $data['firstname']);
        $this->db->bind(':lastname', $data['lastname']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':id', $data['id']);

        return $this->db->execute() ? true : false;
    }

    public function deleteUser($userId) {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }
}