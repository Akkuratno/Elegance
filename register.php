<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connectDB();
    
    if (!$conn) {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка подключения к базе данных'
        ]);
        exit;
    }
    
    $name = cleanInput($_POST['name']);
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo json_encode([
            'success' => false,
            'message' => 'Пароли не совпадают'
        ]);
        exit;
    }
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
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
    
    if ($result->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Пользователь с таким email уже существует'
        ]);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка подготовки запроса'
        ]);
        exit;
    }
    
    $stmt->bind_param("sss", $name, $email, $hashedPassword);
    
    if ($stmt->execute()) {
        session_start();
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['user_name'] = $name;

        echo json_encode([
            'success' => true,
            'message' => 'Регистрация успешна!',
            'redirect' => '../index.html'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка при регистрации: ' . $conn->error
        ]);
    }
    
    $stmt->close();
    $conn->close();
}
