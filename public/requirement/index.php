<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/RequirementService.php';
$requirementService = RequirementService::getInstance();
$layerFilter = $_GET['layer'] ?? null;
$priorityFilter = $_GET['priority'] ?? null;
$nonFunctionalFilter = $_GET['non_functional'] ?? null;
$NonFunctionalFilter = isset($_GET['non_functional']) ? 1 : 0;
$hashtagFilter = $_GET['search_by_hashtag'] ?? '';

$requirementsError = "";
$requirements = array();

try {
    $requirements = $requirementService->getRequirementsByFilters($layerFilter, $priorityFilter, $nonFunctionalFilter);
} catch (Exception $e) {
    $requirementsError = $e->getMessage();
}

?>
<?php require_once __DIR__ . '/../../app/config/lang_config.php'; ?>
<?php require_once __DIR__ . '/../common/header.php'; ?>


<?php
$exportUrl = 'actions/export_requirements.php';

$params = [];
if (!empty($_GET['layer'])) {
    $params['layer'] = $_GET['layer'];
}
if (!empty($_GET['priority'])) {
    $params['priority'] = $_GET['priority'];
}
if (!empty($_GET['non_functional'])) {
    $params['non_functional'] = $_GET['non_functional'];
}

if (!empty($params)) {
    $exportUrl .= '?' . http_build_query($params);
}

$hasFilters = count($params) > 0;

?>

<div class="title-container">
    <h1><?= $translations['requirements']; ?></h1>
    <div class="actions">
        <a class="button" href="./visualization.php"><?= $translations['view_charts']; ?></a>
        <a class="button" href="./add.php"><?= $translations['add_requirement']; ?></a>
        <a class="button <?= count($requirements) ? '' : 'disabled' ?>" href="<?= $exportUrl ?>"
            class="export-button"><?= $translations['export_csv']; ?></a>
        <a class="button" href="#" id="importButton"><?= $translations['import_csv']; ?></a>

        <form id="csvUploadForm" enctype="multipart/form-data" style="display: inline;">
            <input type="file" id="csvFile" name="csvFile" accept=".csv" required style="display: none;">
            <button type="submit" id="submitButton" style="display: none;"><?= $translations['import_file']; ?></button>
        </form>
    </div>
</div>

<div id="uploadStatus"></div>

<div class="filters-container">
    <link rel="stylesheet" type="text/css" href="assets/css/form.css?v=<?= time(); ?>">
    <div class="filters">
        <div class="filter">
            <label for="layerFilter"><?= $translations['filter_by_layer']; ?></label>
            <select id="layerFilter">
                <option value=""><?= $translations['all']; ?></option>
                <option value="client" <?= ($layerFilter === 'client') ? 'selected' : ''; ?>>
                    <?= $translations['client']; ?>
                </option>
                <option value="routing" <?= ($layerFilter === 'routing') ? 'selected' : ''; ?>>
                    <?= $translations['routing']; ?>
                </option>
                <option value="business" <?= ($layerFilter === 'business') ? 'selected' : ''; ?>>
                    <?= $translations['business']; ?>
                </option>
                <option value="db" <?= ($layerFilter === 'db') ? 'selected' : ''; ?>><?= $translations['db']; ?></option>
                <option value="test" <?= ($layerFilter === 'test') ? 'selected' : ''; ?>><?= $translations['test']; ?>
                </option>
            </select>
        </div>
        <div class="filter">
            <label for="priorityFilter"><?= $translations['filter_by_priority']; ?></label>
            <select id="priorityFilter">
                <option value=""><?= $translations['all']; ?></option>
                <option value="high" <?= ($priorityFilter === 'high') ? 'selected' : ''; ?>><?= $translations['high']; ?>
                </option>
                <option value="medium" <?= ($priorityFilter === 'medium') ? 'selected' : ''; ?>>
                    <?= $translations['medium']; ?>
                </option>
                <option value="low" <?= ($priorityFilter === 'low') ? 'selected' : ''; ?>><?= $translations['low']; ?>
                </option>
            </select>
        </div>
        <div class="filter">
            <label for="nonFunctionalFilter"><?= $translations['filter_by_non_functional']; ?></label>
            <select id="nonFunctionalFilter">
                <option value=""><?= $translations['all']; ?></option>
                <option value="1" <?= ($nonFunctionalFilter === '1') ? 'selected' : ''; ?>><?= $translations['yes']; ?>
                </option>
                <option value="0" <?= ($nonFunctionalFilter === '0') ? 'selected' : ''; ?>><?= $translations['no']; ?>
                </option>
            </select>
        </div>
    </div>
    <div class="search-hashtag-container">
        <label for="hashtagSearch"><?= $translations['search_by_hashtag']; ?></label>
        <input type="text" id="hashtagSearch" placeholder="hashtag">
    </div>
    <div class="controls">
        <?php if ($hasFilters): ?>
            <button class="small orange" onclick="clearFilter()"><?= $translations['clear_filter']; ?></button>
        <?php endif; ?>
    </div>
</div>

<div class="content">
    <table class="requirement-table">
        <thead>
            <tr>
                <th>#</th>
                <th><?= $translations['title']; ?></th>
                <th><?= $translations['description']; ?></th>
                <th><?= $translations['hashtags']; ?></th>
                <th><?= $translations['priority']; ?></th>
                <th><?= $translations['layer']; ?></th>
                <th><?= $translations['is_non_functional']; ?></th>
            </tr>
        </thead>
        <tbody id="requirementsBody">
            <?php foreach ($requirements as $idx => $requirement): ?>
                <tr class="requirement-entry" data-id="<?= $requirement['id']; ?>"
                    data-layer="<?= $requirement['layer']; ?>">
                    <td>
                        <?= $idx + 1 ?>
                    </td>
                    <td class="title">
                        <?= $requirement['title']; ?>
                    </td>
                    <td>
                        <?= $requirement['description']; ?>
                    </td>
                    <td><?= $requirement['hashtags'] ?? '-'; ?></td>
                    <td>
                        <?= $translations[$requirement['priority']] ?? $requirement['priority']; ?>
                    </td>
                    <td>
                        <?= $translations[$requirement['layer']] ?? $requirement['layer']; ?>
                    </td>
                    <td>
                        <?= $requirement['isNonFunctional'] ? $translations['yes'] : $translations['no']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= count($requirements) ? '' : $translations['no_req']; ?>
</div>

<script>
    const requirementLoadError = "<?= $requirementsError ?>";
    if (requirementLoadError) {
        showMessage(requirementLoadError);
    }
    document.querySelectorAll('.requirement-entry').forEach(item => {
        item.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const search = window.location.search ? `${window.location.search}&id=${id}` : `?id=${id}`;
            window.location.href = `details.php${search}`;
        });
    });



    document.getElementById('layerFilter').addEventListener('change', updateFilters);
    document.getElementById('priorityFilter').addEventListener('change', updateFilters);
    document.getElementById('nonFunctionalFilter').addEventListener('change', updateFilters);

    function updateFilters() {
        const layer = document.getElementById('layerFilter').value;
        const priority = document.getElementById('priorityFilter').value;
        const nonFunctional = document.getElementById('nonFunctionalFilter').value;
        const url = new URL(window.location.href);

        if (layer) {
            url.searchParams.set('layer', layer);
        } else {
            url.searchParams.delete('layer');
        }

        if (priority) {
            url.searchParams.set('priority', priority);
        } else {
            url.searchParams.delete('priority');
        }

        if (nonFunctional) {
            url.searchParams.set('non_functional', nonFunctional);
        } else {
            url.searchParams.delete('non_functional');
        }

        window.location.href = url.toString();
    }

    function clearFilter() {
        const url = new URL(window.location.href);
        url.searchParams.delete('layer');
        url.searchParams.delete('priority');
        url.searchParams.delete('non_functional');
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

    if (importButton) {
        importButton.addEventListener('click', function(e) {
            e.preventDefault();
            fileInput.click();
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                submitButton.style.display = 'inline';
            } else {
                submitButton.style.display = 'none';
            }
        });
    }

    document.getElementById('hashtagSearch').addEventListener('input', function() {
        let searchValue = this.value.trim().toLowerCase();
        let searchWords = searchValue.split(/\s+/);

        let rows = document.querySelectorAll('.requirement-entry');

        rows.forEach(row => {
            let hashtagCell = row.querySelector('td:nth-child(4)');
            if (hashtagCell) {
                let hashtags = hashtagCell.textContent.toLowerCase().split(/\s+/);

                let matches = searchWords.some(word =>
                    hashtags.some(tag => tag.startsWith(word))
                );

                row.style.display = matches || searchValue === '' ? '' : 'none';
            }
        });

    });
</script>
<?php require_once __DIR__ . '/../common/footer.php'; ?>