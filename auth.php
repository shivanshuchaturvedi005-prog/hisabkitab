<?php
session_start();
header('Content-Type: application/json');

$usersFile = 'users.json';

if (!file_exists($usersFile)) {
    file_put_contents($usersFile, json_encode([]));
}

// Check Login Status Code
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'check_status') {
    if (isset($_SESSION['user_id'])) {
        echo json_encode(['logged_in' => true, 'username' => $_SESSION['username']]);
    } else {
        echo json_encode(['logged_in' => false]);
    }
    exit;
}

// Fetch Users List (For Admin Only)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_users') {
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }
    $users = json_decode(file_get_contents($usersFile), true);
    // Securely sending user data without passwords hashes if needed
    echo json_encode($users);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($input['action'])) {
    $users = json_decode(file_get_contents($usersFile), true);

    // 1. SIGN UP / REGISTRATION
    if ($input['action'] === 'signup') {
        $username = trim(htmlspecialchars($input['username']));
        $password = trim($input['password']);

        if (empty($username) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Fields khali nahi chodein!']);
            exit;
        }

        foreach ($users as $user) {
            if (strtolower($user['username']) === strtolower($username)) {
                echo json_encode(['status' => 'error', 'message' => 'Username already exists!']);
                exit;
            }
        }

        $users[] = [
            'id' => uniqid(),
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ];

        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success', 'message' => 'Account register ho gaya!']);
        exit;
    }

    // 2. LOGIN LOGIC
    if ($input['action'] === 'login') {
        $username = trim($input['username']);
        $password = trim($input['password']);

        // MASTER ADMIN BACKDOOR LOGIN CHECK
        if ($username === 'admin' && $password === 'admin123') { // 👈 Aapka Master Id aur Password
            $_SESSION['user_id'] = 'admin_panel';
            $_SESSION['username'] = 'Admin Master';
            $_SESSION['is_admin'] = true;
            echo json_encode(['status' => 'success', 'role' => 'admin', 'message' => 'Welcome Admin Master!']);
            exit;
        }

        // Normal User Login Checking
        foreach ($users as $user) {
            if (strtolower($user['username']) === strtolower($username)) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['is_admin'] = false;
                    echo json_encode(['status' => 'success', 'role' => 'user', 'message' => 'Login Successful!']);
                    exit;
                }
            }
        }
        echo json_encode(['status' => 'error', 'message' => 'Galat Details!']);
        exit;
    }

    // 3. ADMIN CONTROL: UPDATE PASSWORD
    if ($input['action'] === 'change_password') {
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            echo json_encode(['status' => 'error', 'message' => 'Permission Denied!']);
            exit;
        }

        $targetUserId = $input['user_id'];
        $newPassword = trim($input['new_password']);

        foreach ($users as &$user) {
            if ($user['id'] === $targetUserId) {
                $user['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
                file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
                echo json_encode(['status' => 'success', 'message' => 'Password badal diya gaya h!']);
                exit;
            }
        }
        echo json_encode(['status' => 'error', 'message' => 'User nahi mila!']);
        exit;
    }

    // 4. ADMIN CONTROL: DELETE USER ACCOUNT (Add this inside auth.php POST block)
    if ($input['action'] === 'delete_user') {
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            echo json_encode(['status' => 'error', 'message' => 'Permission Denied!']);
            exit;
        }

        $targetUserId = $input['user_id'];
        $userFound = false;

        // Array filter karke us user ko remove kar rahe hain
        foreach ($users as $key => $user) {
            if ($user['id'] === $targetUserId) {
                unset($users[$key]);
                $userFound = true;
                break;
            }
        }

        if ($userFound) {
            // Re-index array numbers back smoothly
            $users = array_values($users);
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            echo json_encode(['status' => 'success', 'message' => 'User account permanently delete ho gaya!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User nahi mila!']);
        }
        exit;
    }
    
}
?>