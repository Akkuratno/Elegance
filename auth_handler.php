<?php
require_once 'config.php';

session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo json_encode(checkAuth());
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        $conn = connectDB();
        
        if (!$conn) {
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка подключения к базе данных'
            ]);
            exit;
        }
        
        $email = cleanInput($_POST['email']);
        $password = $_POST['password'];
        
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        if (!$stmt) {
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка подготовки запроса'
            ]);
            exit;
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Вход выполнен успешно!',
                    'redirect' => '../index.html'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Неверный пароль'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Пользователь не найден'
            ]);
        }
        
        $stmt->close();
        $conn->close();
    }
    
    if (isset($_POST['action']) && $_POST['action'] == 'logout') {
        session_destroy();
        
        echo json_encode([
            'success' => true,
            'message' => 'Выход выполнен успешно',
            'redirect' => '../index.html'
        ]);
    }
}
