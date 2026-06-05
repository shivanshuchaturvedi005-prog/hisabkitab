<?php
header('Content-Type: application/json');

$jsonFile = 'expenses.json';

// Agar file nahi hai toh khali array banado
if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, json_encode([]));
}

// Data read karne ke liye (GET Request)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo file_get_contents($jsonFile);
    exit;
}

// Data save ya delete karne ke liye (POST Request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $currentData = json_decode(file_get_contents($jsonFile), true);

    // 1. DELETE ACTION
    if (isset($input['action']) && $input['action'] === 'delete') {
        $idTo_delete = $input['id'];
        $currentData = array_filter($currentData, function($item) use ($idTo_delete) {
            return $item['id'] !== $idTo_delete;
        });
        // Array ki keys ko reset karna zaroori hai
        $currentData = array_values($currentData);
        
        file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success', 'message' => 'Kharche ka hisab delete ho gaya!']);
        exit;
    }

    // 2. ADD ACTION (Updated with payeeName)
    if (isset($input['itemName']) && isset($input['amount'])) {
        $newExpense = [
            'id' => uniqid(), // Har entry ke liye unique ID
            'itemName' => htmlspecialchars(trim($input['itemName'])),
            'amount' => (float)$input['amount'],
            'payeeName' => isset($input['payeeName']) ? htmlspecialchars(trim($input['payeeName'])) : 'Unknown', // Naya logic yahan joda gaya hai
            'category' => $input['category'],
            'date' => $input['date']
        ];

        array_push($currentData, $newExpense);
        file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT));

        echo json_encode(['status' => 'success', 'message' => 'Hisab save ho gaya!']);
        exit;
    }
}
?>