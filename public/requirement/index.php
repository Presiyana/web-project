<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/RequirementService.php';
$requirementService = RequirementService::getInstance();
$layerFilter = $_GET['layer'] ?? null;
$requirements = $requirementService->getRequirementsByLayer($layerFilter);

?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<?php
$exportUrl = '.';
if (count($requirements)) {
    $exportUrl = 'actions/export_requirements.php';
    if (isset($_GET['layer'])) {
        $exportUrl .= '?layer=' . urlencode($_GET['layer']);
    }
}
?>

<div class="title-container">
    <h1>Requirements</h1>
    <div class="actions">
        <a class="button" href="./add.php">Add Requirement</a>
        <a class="button <?= count($requirements) ? '' : 'disabled' ?>" href="<?= $exportUrl ?>" class="export-button">Export to CSV</a>
        <a class="button" href="#" id="importButton">Import from CSV</a>

        <form id="csvUploadForm" enctype="multipart/form-data" style="display: inline;">
        <input type="file" id="csvFile" name="csvFile" accept=".csv" required style="display: none;">
        <button type="submit" id="submitButton" style="display: none;">Import the selected file</button>
        </form>
    </div>
</div>

<div id="uploadStatus"></div>

<div class="filters-container">
    <div class="filters">
        <div class="filter">
            <label for="layerFilter">Filter by Layer:</label>
            <select id="layerFilter">
                <option value="">All</option>
                <option value="client" <?= ($layerFilter === 'client') ? 'selected' : ''; ?>>Client</option>
                <option value="routing" <?= ($layerFilter === 'routing') ? 'selected' : ''; ?>>Routing</option>
                <option value="business" <?= ($layerFilter === 'business') ? 'selected' : ''; ?>>Business</option>
                <option value="db" <?= ($layerFilter === 'db') ? 'selected' : ''; ?>>DB</option>
                <option value="test" <?= ($layerFilter === 'test') ? 'selected' : ''; ?>>Test</option>
            </select>
        </div>
    </div>
    <div class="controls">
        <button id="clearFilter" onclick="clearFilter()">Clear Filter</button>
    </div>
</div>

<div class="content">
    <table class="requirement-table">
        <thead>
            <tr>
                <th>#</th>
                <th>title</th>
                <th>description</th>
                <th>priority</th>
                <th>layer</th>
                <th>Is Non-Functional</th>
                <th>Indicator Name</th>
                <th>Unit</th>
                <th>Value</th>
                <th>Indicator Description</th>
            </tr>
        </thead>
        <tbody id="requirementsBody">
            <?php foreach ($requirements as $idx => $requirement): ?>
                <tr class="requirement-entry" data-id="<?= $requirement['id']; ?>"
                    data-layer="<?= $requirement['layer']; ?>">
                    <td>
                        <?= $idx + 1 ?>
                    </td>
                    <td>
                        <?= $requirement['title']; ?>
                    </td>
                    <td>
                        <?= $requirement['description']; ?>
                    </td>
                    <td>
                        <?= $requirement['priority']; ?>
                    </td>
                    <td>
                        <?= $requirement['layer']; ?>
                    </td>
                    <td>
                        <?= $requirement['isNonFunctional'] ? 'Yes' : 'No'; ?>
                    </td>
                    <td>
                        <?= $requirement['indicator_name'] ?? 'N/A'; ?>
                    </td>
                    <td>
                        <?= $requirement['unit'] ?? 'N/A'; ?>
                    </td>
                    <td>
                        <?= $requirement['value'] ?? 'N/A'; ?>
                    </td>
                    <td>
                        <?= $requirement['indicator_description'] ?? 'N/A'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= count($requirements) ? '' : 'No requirements' ?>

</div>

<script>
    // Handle Row Click for Editing
    document.querySelectorAll('.requirement-entry').forEach(item => {
        item.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            window.location.href = `details.php?id=${id}`;
        });
    });

    // Filter Requirements Based on Layer Selection
    document.getElementById('layerFilter').addEventListener('change', function () {
        const selectedLayer = this.value;
        const url = new URL(window.location.href);

        if (selectedLayer) {
            url.searchParams.set('layer', selectedLayer);
        } else {
            url.searchParams.delete('layer');
        }

        // Reload the page with the new filter
        window.location.href = url.toString();
    });

    // Clear Filter
    function clearFilter() {
        const url = new URL(window.location.href);
        url.searchParams.delete('layer');
        window.location.href = url.toString();
    }

    document.getElementById("csvUploadForm").addEventListener("submit", function(event) {
        event.preventDefault();

        var formData = new FormData();
        var fileInput = document.getElementById("csvFile");

        if (fileInput.files.length === 0) {
            document.getElementById("uploadStatus").innerHTML = "<p style='color:red;'>Please select a CSV file.</p>";
            return;
        }

        formData.append("csvFile", fileInput.files[0]);

        fetch("actions/import_requirements.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("uploadStatus").innerHTML = `<p style='color: green;'>${data.message}</p>`;
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                let errorMessage = `<p style='color: red;'>${data.message}</p>`;
                if (data.errors) {
                    errorMessage += "<ul>";
                    data.errors.forEach(error => {
                        errorMessage += `<li>${error}</li>`;
                    });
                    errorMessage += "</ul>";
                }
                document.getElementById("uploadStatus").innerHTML = errorMessage;
            }
        })
        .catch(error => {
            document.getElementById("uploadStatus").innerHTML = "<p style='color:red;'>Error uploading file.</p>";
        });
    });

    const importButton = document.getElementById('importButton');
    const fileInput = document.getElementById('csvFile');
    const submitButton = document.getElementById('submitButton');
    const form = document.getElementById('csvUploadForm');

    
    importButton.addEventListener('click', function(e) {
        e.preventDefault();  
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            submitButton.style.display = 'inline';
        } else {
            submitButton.style.display = 'none'; 
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('File uploaded successfully!');
    });
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>