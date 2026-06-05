<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { echo json_encode([]); exit; }

$file = 'timetable.json';
$userId = $_SESSION['user_id'];

if (!file_exists($file)) { file_put_contents($file, json_encode([])); }
$allData = json_decode(file_get_contents($file), true);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Sirf current user ka data send karein
    $userData = array_filter($allData, function($item) use ($userId) {
        return $item['user_id'] === $userId;
    });
    echo json_encode(array_values($userData));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['action']) && $input['action'] === 'delete') {
        $allData = array_filter($allData, function($item) use ($input) {
            return $item['id'] !== $input['id'];
        });
        file_put_contents($file, json_encode(array_values($allData), JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success']);
        exit;
    }

    if (isset($input['action']) && $input['action'] === 'add') {
        $allData[] = [
            'id' => uniqid(),
            'user_id' => $userId,
            'day' => $input['day'],
            'timeSlot' => htmlspecialchars($input['timeSlot']),
            'task' => htmlspecialchars($input['task'])
        ];
        file_put_contents($file, json_encode($allData, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success']);
        exit;
    }
}
?>