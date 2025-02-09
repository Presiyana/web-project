<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/services/RequirementService.php';
?>

<?php require_once __DIR__ . '/../common/header.php'; ?>


<div class="title-container">
    <h1><?= $translations['create_a_req']; ?></h1>
</div>
<div class="content">
    <form class="box" class="requirement-form" action="actions/add_requirement_action.php" method="post">
        <label for="title"><?= $translations['title']; ?></label>
        <input type="text" id="title" name="title" required>

        <label for="description"><?= $translations['description']; ?></label>
        <input type="text" id="description" name="description" required>

        <label for="priority"><?= $translations['priority']; ?></label>
        <select name="priority" id="priority">
            <option value="high"><?= $translations['high']; ?></option>
            <option value="medium"><?= $translations['medium']; ?></option>
            <option value="low"><?= $translations['low']; ?></option>
        </select>

        <label for="layer"><?= $translations['layer']; ?></label>
        <select name="layer" id="layer">
            <option value="client"><?= $translations['client']; ?></option>
            <option value="routing"><?= $translations['routing']; ?></option>
            <option value="business"><?= $translations['business']; ?></option>
            <option value="db"><?= $translations['db']; ?></option>
            <option value="test"><?= $translations['test']; ?></option>
        </select>

        <label for="hashtags"><?= $translations['hashtags']; ?></label>
        <input type="text" id="hashtags" name="hashtags" required>

        <label for="isNonFunctional"><?= $translations['is_non_functional']; ?></label>
        <input type="checkbox" id="isNonFunctional" name="isNonFunctional" value="1">
    
        <div id="nonFunctionalFields" style="display:none;">
            <label for="indicator_name"><?= $translations['indicator_name']; ?></label>
            <input type="text" id="indicator_name" name="indicator_name">

            <label for="unit"><?= $translations['unit']; ?></label>
            <input type="text" id="unit" name="unit">

            <label for="value"><?= $translations['value']; ?></label>
            <input type="number" step="0.01" id="value" name="value">

            <label for="indicator_description"><?= $translations['indicator_description']; ?></label>
            <textarea id="indicator_description" name="indicator_description"></textarea>
        </div>
        
        <button type="submit"><?= $translations['submit']; ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../common/footer.php'; ?>