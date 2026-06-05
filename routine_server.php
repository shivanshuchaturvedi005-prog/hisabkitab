<?php
header('Content-Type: application/json');
$jsonFile = 'routine.json';

if (!file_exists($jsonFile)) { file_put_contents($jsonFile, json_encode([])); }

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo file_get_contents($jsonFile);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $currentData = json_decode(file_get_contents($jsonFile), true);

    if (isset($input['action']) && $input['action'] === 'delete') {
        $id = $input['id'];
        $currentData = array_values(array_filter($currentData, function($item) use ($id) { return $item['id'] !== $id; }));
        file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success']);
        exit;
    }

    if (isset($input['task'])) {
        $currentData[] = [
            'id' => uniqid(),
            'partnerName' => htmlspecialchars($input['partnerName']),
            'task' => htmlspecialchars($input['task']),
            'day' => $input['day'],
            'time' => $input['time']
        ];
        file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success']);
        exit;
    }
}
?>