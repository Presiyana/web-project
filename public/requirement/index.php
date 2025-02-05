<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/RequirementService.php';
$requirementService = RequirementService::getInstance();
$layerFilter = $_GET['layer'] ?? null;
$requirements = $requirementService->getRequirementsByLayer($layerFilter);

?>
<?php require_once __DIR__ . '/../../app/config/lang_config.php'; ?>
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
    <h1><?= $translations['add_requirement']; ?></h1>
    <div class="actions">
        <a class="button" href="./add.php"><?= $translations['add_requirement']; ?></a>
        <a class="button <?= count($requirements) ? '' : 'disabled' ?>" href="<?= $exportUrl ?>" class="export-button"><?= $translations['export_csv']; ?></a>
        <a class="button" href="#" id="importButton"><?= $translations['import_csv']; ?></a>

        <form id="csvUploadForm" enctype="multipart/form-data" style="display: inline;">
        <input type="file" id="csvFile" name="csvFile" accept=".csv" required style="display: none;">
        <button type="submit" id="submitButton" style="display: none;"><?= $translations['import_file']; ?></button>
        </form>
    </div>
</div>

<div id="uploadStatus"></div>

<div class="filters-container">
<div class="filters">
        <div class="filter">
            <label for="layerFilter"><?= $translations['filter_by_layer']; ?></label>
            <select id="layerFilter">
                <option value=""><?= $translations['all']; ?></option>
                <option value="client" <?= ($layerFilter === 'client') ? 'selected' : ''; ?>><?= $translations['client']; ?></option>
                <option value="routing" <?= ($layerFilter === 'routing') ? 'selected' : ''; ?>><?= $translations['routing']; ?></option>
                <option value="business" <?= ($layerFilter === 'business') ? 'selected' : ''; ?>><?= $translations['business']; ?></option>
                <option value="db" <?= ($layerFilter === 'db') ? 'selected' : ''; ?>><?= $translations['db']; ?></option>
                <option value="test" <?= ($layerFilter === 'test') ? 'selected' : ''; ?>><?= $translations['test']; ?></option>
            </select>
        </div>
    </div>
    <div class="controls">
        <button id="clearFilter" onclick="clearFilter()"><?= $translations['clear_filter']; ?></button>
    </div>
</div>

<div class="content">
    <table class="requirement-table">
        <thead>
            <tr>
                <th>#</th>
                <th><?= $translations['title']; ?></th>
                <th><?= $translations['description']; ?></th>
                <th><?= $translations['priority']; ?></th>
                <th><?= $translations['layer']; ?></th>
                <th><?= $translations['is_non_functional']; ?></th>
                <th><?= $translations['indicator_name']; ?></th>
                <th><?= $translations['unit']; ?></th>
                <th><?= $translations['value']; ?></th>
                <th><?= $translations['indicator_description']; ?></th>
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
                        <?= $translations[$requirement['priority']] ?? $requirement['priority']; ?>
                    </td>
                    <td>
                        <?= $translations[$requirement['layer']] ?? $requirement['layer']; ?>
                    </td>
                    <td>
                        <?= $requirement['isNonFunctional'] ? $translations['yes'] : $translations['no']; ?>
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
    <?= count($requirements) ? '' : $translations['no_req']; ?>

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
            document.getElementById("uploadStatus").innerHTML = "<p style='color:red;'><?= $translations['select_csv']; ?></p>";
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
            document.getElementById("uploadStatus").innerHTML = "<p style='color:red;'><?= $translations['error_uploading_file']; ?></p>";
        });
    });

    const importButton = document.getElementById('importButton');
    const fileInput = document.getElementById('csvFile');
    const submitButton = document.getElementById('submitButton');

    if(importButton){
        importButton.addEventListener('click', function(e) {
            e.preventDefault();  
            fileInput.click();
        });
    }

    if(fileInput){
        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                submitButton.style.display = 'inline';
            } else {
                submitButton.style.display = 'none'; 
            }
        });
    }
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>