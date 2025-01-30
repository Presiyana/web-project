<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/RequirementService.php';
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>


<div class="title">
    <h1>Create a requirement</h1>
</div>
<div class="content">
    <form class="requirement-form" action="actions/add_requirement_action.php" method="post">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description" required>

        <label for="priority">Priority:</label>
        <select name="priority" id="priority">
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
        </select>

        <label for="layer">Layer:</label>
        <select name="layer" id="layer">
            <option value="client">Client</option>
            <option value="routing">Routing</option>
            <option value="business">Business</option>
            <option value="db">DB</option>
            <option value="test">Test</option>
        </select>

        <label for="hashtags">Hashtags:</label>
        <input type="text" id="hashtags" name="hashtags" required>

        <label for="isNonFunctional">Is Non-Functional?</label>
        <input type="checkbox" id="isNonFunctional" name="isNonFunctional" value="1">
    
        <div id="nonFunctionalFields" style="display:none;">
            <label for="indicator_name">Indicator Name:</label>
            <input type="text" id="indicator_name" name="indicator_name">

            <label for="unit">Unit:</label>
            <input type="text" id="unit" name="unit">

            <label for="value">Value:</label>
            <input type="number" step="0.01" id="value" name="value">

            <label for="indicator_description">Indicator Description:</label>
            <textarea id="indicator_description" name="indicator_description"></textarea>
        </div>
        
        <button type="submit">Submit</button>
    </form>
</div>

<script>
    const isNonFunctionalCheckbox = document.getElementById('isNonFunctional');
    const nonFunctionalFields = document.getElementById('nonFunctionalFields');
                  
    isNonFunctionalCheckbox.addEventListener('change', () => {
        if (isNonFunctionalCheckbox.checked) {
            nonFunctionalFields.style.display = 'block';
        } else {
            nonFunctionalFields.style.display = 'none';
        }
    });
</script>

<?php require_once __DIR__ . '/../common/footer.php'; ?>