<?php
require_once __DIR__ . '/../../../app/services/RequirementService.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["csvFile"])) {
    $file = $_FILES["csvFile"]["tmp_name"];

    if (!file_exists($file)) {
        echo json_encode(["success" => false, "message" => "File upload failed."]);
        exit;
    }

    $handle = fopen($file, "r");
    if (!$handle) {
        echo json_encode(["success" => false, "message" => "Cannot open CSV file."]);
        exit;
    }

    $requirementService = RequirementService::getInstance();
    $rowCount = 0;
    $importedCount = 0;
    $errors = [];

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if ($rowCount === 0) {
            $rowCount++;
            continue;
        }

        if (empty($data[1]) || empty($data[2]) || empty($data[3]) || empty($data[4]) || empty($data[5])) {
            $errors[] = "Row $rowCount: Missing required fields.";
            $rowCount++;
            continue;
        }

        $title = $data[1] ?? '';
        $description = $data[2] ?? '';
        $hashtags = $data[3] ?? '';
        $priority = $data[4] ?? '';
        $layer = $data[5] ?? '';
        $isNonFunctional = strtolower(trim($data[6])) === "yes";
        $indicators = json_decode($data[8], true);

        $allowedPriorities = ['high', 'medium', 'low'];
        $allowedLayers = ['client', 'routing', 'business', 'db', 'test'];

        if (!in_array($priority, $allowedPriorities)) {
            $errors[] = "Row $rowCount: Invalid priority value.";
            $rowCount++;
            continue;
        }

        if (!in_array($layer, $allowedLayers)) {
            $errors[] = "Row $rowCount: Invalid layer value.";
            $rowCount++;
            continue;
        }

        // Insert into database
        try {
            $requirementService->addRequirement(
                $title,
                $description,
                $hashtags,
                $priority,
                $layer,
                $isNonFunctional,
                $indicators
            );
            $importedCount++;
        } catch (Exception $e) {
            $errors[] = "Row $rowCount: " . $e->getMessage();
        }


        $rowCount++;
    }

    fclose($handle);
    if (empty($errors)) {
        echo json_encode(["success" => true, "message" => "CSV imported successfully! $importedCount requirements added."]);
    } else {
        echo json_encode(["success" => false, "message" => "Partial import completed. $importedCount requirements added.", "errors" => $errors]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
