<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/RequirementService.php';
$requirementService = RequirementService::getInstance();
$requirements = $requirementService->getRequirements();
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>

<div class="title">
    <h1>Requirements</h1>
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
        <tbody>
            <?php foreach ($requirements as $idx => $requirement): ?>
                <tr class="requirement-entry" attr-id="<?= $requirement['id']; ?>">
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
</div>

<script>
    const items = document.getElementsByClassName('requirement-entry');
    for (let item of items) {
        item.addEventListener('click', (event) => {
            const id = event.currentTarget.getAttribute('attr-id');
            window.location.href = `${id}/edit`;
        });
    }
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>