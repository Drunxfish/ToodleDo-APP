<?php

require_once 'db.php';
$userDB = new User();



class User
{
    public $pdo;

    public function __construct()
    {
        $this->pdo = new Database();
    }


    // function to handle user registrations
    public function signupUser($username, $email, $password)
    {
        $hashedPWD = password_hash($password, PASSWORD_DEFAULT);

        // inserts  data or not and returns boolean accordingly
        return $this->pdo->run("INSERT INTO users (username, email, password) 
        VALUES (:username, :email, :password)",
            [
                "username" => $username,
                "email" => $email,
                "password" => $hashedPWD,
            ]
        );
    }

    // returns user data
    public function logUser($email)
    {
        return $this->pdo->run("SELECT * FROM users WHERE email = :email", ["email" => $email])->fetch();
    }



    // returns email or false boolean
    public function selectEmail($email)
    {
        return $this->pdo->run("SELECT email FROM users WHERE email = :email", ['email' => $email])->rowCount();
    }



    # Updating user profile
    public function updateProfile($username = null, $currentPwd = null, $newPwd = null, $pfpPath = null, $id)
    {
        // Placeholders/Params
        $fields = [];
        $params = ["id" => $id];

        // Username update
        if ($username !== null) {
            $fields[] = "username = :username";
            $params["username"] = $username;
        }

        // Password update 
        if (!empty($newPwd)) {

            $currentUser = $this->pdo->run("SELECT password FROM users WHERE id = :id", ["id" => $id])->fetch();
            if ($currentUser && password_verify($currentPwd, $currentUser['password'])) {
                $fields[] = "password = :password";
                $params["password"] = password_hash($newPwd, PASSWORD_DEFAULT);
            } else {
                return "Incorrect current password!"; // Incorrect password
            }
        }

        // Profile picture update
        if (isset($pfpPath) && $pfpPath['error'] == UPLOAD_ERR_OK) {
            $fileName = $pfpPath['name'];
            $fileInfo = pathinfo($fileName);

            // Validate file extension
            if (!in_array(strtolower($fileInfo['extension']), ['png', 'jpg', 'jpeg'])) {
                return "Invalid file type. Only PNG, JPG, and JPEG allowed.";
            }

            // Set upload directory
            $uploadDir = "./../Assets/profile/";
            $filePath = $uploadDir . $fileName;

            // Rename if file already exists
            if (file_exists($filePath)) {
                $uniqueName = $fileInfo['filename'] . "_" . time() . "." . $fileInfo['extension'];
                $filePath = $uploadDir . $uniqueName;
            }

            // Save file 
            if (!move_uploaded_file($pfpPath['tmp_name'], $filePath)) {
                return "Failed to upload profile picture.";
            }

            // Store QRY/Path
            $fields[] = "profile_pic_path = :pfpPath";
            $params["pfpPath"] = $filePath;
        }

        // Execute query if there are changes
        if (!empty($fields)) {
            $stmt = $this->pdo->run("UPDATE users SET " . implode(", ", $fields) . " WHERE id = :id", $params)->rowCount() > 0;

            // Update session if successful
            if ($stmt) {
                // Removing old profile picture
                if (isset($_SESSION['profile_path']) && file_exists($_SESSION['profile_path'])) {
                    unlink($_SESSION['profile_path']);
                }

                // Updating individual session values
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'pfpPath':
                            $_SESSION['profile_path'] = $value;
                            break;
                        case 'username':
                            $_SESSION['username'] = $value;
                            break;
                    }
                }
                return true;
            }
        }
        return false;
    }
}
