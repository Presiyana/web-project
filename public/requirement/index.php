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
$exportUrl = 'actions/export_requirements.php';
if (isset($_GET['layer'])) {
    $exportUrl .= '?layer=' . urlencode($_GET['layer']);
}
?>

<div class="title">
    <h1>Requirements</h1>
</div>

<label for="layerFilter">Filter by Layer:</label>
<select id="layerFilter">
    <option value="">All</option>
    <option value="client" <?= ($layerFilter === 'client') ? 'selected' : ''; ?>>Client</option>
    <option value="routing" <?= ($layerFilter === 'routing') ? 'selected' : ''; ?>>Routing</option>
    <option value="business" <?= ($layerFilter === 'business') ? 'selected' : ''; ?>>Business</option>
    <option value="db" <?= ($layerFilter === 'db') ? 'selected' : ''; ?>>DB</option>
    <option value="test" <?= ($layerFilter === 'test') ? 'selected' : ''; ?>>Test</option>
</select>
<button id="clearFilter" onclick="clearFilter()">Clear Filter</button>

<a href="<?= $exportUrl ?>" class="export-button">Export to CSV</a>

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
                <tr class="requirement-entry" data-id="<?= $requirement['id']; ?>" data-layer="<?= $requirement['layer']; ?>">
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
    <?= count($requirements) ? '' : 'No requirements'?>

</div>

<script>
    // Handle Row Click for Editing
    document.querySelectorAll('.requirement-entry').forEach(item => {
        item.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            window.location.href = `edit.php?id=${id}`;
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
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>