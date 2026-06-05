<?php
header('Content-Type: application/json');

$jsonFile = 'udhaar.json';

if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, json_encode([]));
}

// Data Get karne ke liye
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo file_get_contents($jsonFile);
    exit;
}

// Data Process/Settle/Save karne ke liye
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $currentData = json_decode(file_get_contents($jsonFile), true);

    // SETTLE ACTION (Updates status to settled instead of removing data completely)
    if (isset($input['action']) && $input['action'] === 'settle') {
        $idToSettle = $input['id'];
        
        foreach ($currentData as &$item) {
            if ($item['id'] === $idToSettle) {
                $item['status'] = 'settled';
                break;
            }
        }
        
        file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success', 'message' => 'Hisab barabar ho gaya!']);
        exit;
    }

    // ADD ACTION
    if (!empty($input['friendFrom']) && !empty($input['friendTo']) && isset($input['amount'])) {
        $newUdhaar = [
            'id' => uniqid(),
            'friendFrom' => htmlspecialchars(trim($input['friendFrom'])),
            'friendTo' => htmlspecialchars(trim($input['friendTo'])),
            'amount' => (float)$input['amount'],
            'description' => htmlspecialchars(trim($input['description'])),
            'date' => $input['date'],
            'status' => 'pending' // Nayi entries automatic pending me jayengi
        ];

        array_push($currentData, $newUdhaar);
        file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success', 'message' => 'Udhaar ka hisab save ho gaya!']);
        exit;
    }
}
?>