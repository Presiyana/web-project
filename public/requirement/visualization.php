<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/config/lang_config.php';
require_once __DIR__ . '/../../app/services/RequirementService.php';
$requirementService = RequirementService::getInstance();

$requirements = $requirementService->getAllRequirements(); 

// Count priorities
$priorityCount = ['high' => 0, 'medium' => 0, 'low' => 0];
$layerCount = [];

foreach ($requirements as $requirement) {
    $priority = strtolower($requirement['priority']);
    if (isset($priorityCount[$priority])) {
        $priorityCount[$priority]++;
    }

    $layer = strtolower($requirement['layer']);
    if (!isset($layerCount[$layer])) {
        $layerCount[$layer] = 0;
    }
    $layerCount[$layer]++;
}

$layerTranslations = [
    'business' => $translations['business'] ?? 'Business',
    'client' => $translations['client'] ?? 'Client',
    'routing' => $translations['routing'] ?? 'Routing',
    'test' => $translations['test'] ?? 'Test',
    'db' => $translations['db'] ?? 'DB'
];

$layerTranslationsJson = json_encode($layerTranslations);

$priorityTranslations = [
    'high' => $translations['high'] ?? 'High',
    'medium' => $translations['medium'] ?? 'Medium',
    'low' => $translations['low'] ?? 'Low'
];

$priorityTranslationsJson = json_encode($priorityTranslations);

$priorityData = json_encode($priorityCount);
$layerData = json_encode($layerCount);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requirement Visualization</title>
    <style>
        .chart-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        canvas {
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .chart-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h2><?= $translations['req_data_visualization']; ?></h2>

    <div class="chart-container">
        <div>
            <p class="chart-title"><?= $translations['priority_distribution']; ?></p>
            <canvas id="priorityChart" width="600" height="300"></canvas>
        </div>
        <div>
            <p class="chart-title"><?= $translations['req_by_layer']; ?></p>
            <canvas id="layerChart" width="600" height="300"></canvas>
        </div>
    </div>

    <script>
        const priorityData = <?= $priorityData ?>;
        const layerData = <?= $layerData ?>;
        const layerTranslations = <?= $layerTranslationsJson ?>;
        const priorityTranslations = <?= $priorityTranslationsJson ?>;

        function drawPieChart(canvasId, data, translations) {
            const canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext('2d');
            const colors = ['#FF0000', '#FFC300', '#33cc33'];
            const labels = Object.keys(data);
            const values = Object.values(data);

            let total = values.reduce((sum, val) => sum + val, 0);
            let startAngle = 0;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (let i = 0; i < values.length; i++) {
                let sliceAngle = (values[i] / total) * (2 * Math.PI);

                ctx.beginPath();
                ctx.moveTo(150, 150);
                ctx.arc(150, 150, 100, startAngle, startAngle + sliceAngle);
                ctx.closePath();
                ctx.fillStyle = colors[i];
                ctx.fill();

                startAngle += sliceAngle;
            }

            let legendY = 10;
            ctx.font = "14px Arial";
            for (let i = 0; i < labels.length; i++) {
                ctx.fillStyle = colors[i];
                ctx.fillRect(270, legendY, 15, 15);
                ctx.fillStyle = "#000";
                ctx.fillText(translations[labels[i]] || labels[i], 290, legendY + 12);
                legendY += 20;
            }
        }

        function drawBarChart(canvasId, data, translations) {
            const canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext('2d');
            const labels = Object.keys(data);
            const values = Object.values(data);
            const barWidth = 50;
            const gap = 30;
            const startX = 50;
            const maxHeight = 150;
            const paddingBottom = 100;
            const baseY = canvas.height - paddingBottom;

            let maxValue = Math.max(...values);
            let scaleFactor = maxHeight / maxValue;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (let i = 0; i < values.length; i++) {
                let barHeight = values[i] * scaleFactor;
                let x = startX + (barWidth + gap) * i;
                let y = baseY - barHeight;

                ctx.fillStyle = '#6495ed';
                ctx.fillRect(x, y, barWidth, barHeight);

                ctx.fillStyle = '#000';
                ctx.font = "16px Arial";
                ctx.fillText(values[i], x + barWidth / 4, y - 10);

                ctx.save();
                ctx.translate(x + barWidth / 2, baseY + 40); 
                ctx.rotate(-Math.PI / 2); 
                ctx.textAlign = "center";
                ctx.fillText(translations[labels[i]] || labels[i], 0, 0);
                ctx.restore();
            }
        }

        drawPieChart('priorityChart', priorityData, priorityTranslations);
        drawBarChart('layerChart', layerData, layerTranslations);
    </script>

</body>
</html>
