<?php
header('Content-Type: application/json');

$jsonFile = 'room.json';
$uploadDir = 'uploads/';

if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, json_encode([]));
}

// Data Get karne ke liye
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo file_get_contents($jsonFile);
    exit;
}

// Data Save/Delete karne ke liye
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. DELETE ACTION (Form-data format me handle karenge)
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $idTo_delete = $_POST['id'];
        $currentData = json_decode(file_get_contents($jsonFile), true);
        
        // Image delete karne ke liye pehle file check karenge
        foreach ($currentData as $item) {
            if ($item['id'] === $idTo_delete && !empty($item['image'])) {
                if (file_exists($item['image'])) {
                    unlink($item['image']); // Folder se image delete kar dega
                }
            }
        }

        $currentData = array_filter($currentData, function($item) use ($idTo_delete) {
            return $item['id'] !== $idTo_delete;
        });
        
        file_put_contents($jsonFile, json_encode(array_values($currentData), JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success', 'message' => 'Record delete ho gaya!']);
        exit;
    }

    // 2. ADD ACTION (Form-data subbmit hoga kyunki file upload hai)
    if (isset($_POST['monthName'])) {
        $imagePath = "";

        // File upload logic
        if (isset($_FILES['meterImage']) && $_FILES['meterImage']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['meterImage']['tmp_name'];
            $fileName = $_FILES['meterImage']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            // Safe unique file name banana
            $newFileName = uniqid('meter_', true) . '.' . $fileExtension;
            $imagePath = $uploadDir . $newFileName;

            // File ko uploads folder me move karna
            move_uploaded_file($fileTmpPath, $imagePath);
        }

        $currentData = json_decode(file_get_contents($jsonFile), true);

        $newRoomRecord = [
            'id' => uniqid(),
            'monthName' => htmlspecialchars($_POST['monthName']),
            'rentAmount' => (float)$_POST['rentAmount'],
            'meterReading' => htmlspecialchars($_POST['meterReading']),
            'ebillAmount' => (float)$_POST['ebillAmount'],
            'image' => $imagePath, // Yahan image ka path save hoga
            'date' => date('Y-m-d')
        ];

        array_push($currentData, $newRoomRecord);
        file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT));
        
        echo json_encode(['status' => 'success', 'message' => 'Room data save ho gaya!']);
        exit;
    }
}
?>