<?php

require_once 'core/init.php';

// check if new school submitted
if (isset($_POST['submit'])) {
    // sanitize
    $sanitizer->run($_POST, [
        'school-name' => 'trim,sanitize',
    ]);
    $sanitized = $sanitizer->getFilteredData();

    // then validate
    $validator->run($sanitized, [
        'school-name' => 'required,alpha_num_space',
    ]);
    $validated = $validator->getValidatedData();

    // check for validation errors
    if ($validator->hasErrors()) {
        $errors = $validator->getAllValidationErrors();
    } else {
        // insert into database
        $db->insert('schools', [
            'name' => $validated['school-name']
        ]);
    }
}

// get all schools
$db->select('schools');
$schools = $db->fetchAll();

?>

<?php require_once 'includes/header.php'; ?>

    <div class="container">
        <section class="board">
            <header class="board-head">
                <div class="centered h5">
                    <h2>Add school</h2>
                </div>
            </header>
            <div class="board-body">
                <?php if (isset($errors)) {
                    foreach ($errors as $error) { ?>
                        <div class="alert danger"><?php echo $error; ?></div>
                    <?php }
                } elseif (isset($_POST['submit'])) { ?>
                    <div class="alert success">Success! New school has been added</div>
                <?php } ?>

                <form action="add-school.php" method="POST">
                    <input type="text" name="school-name" placeholder="school name" class="field-style-2"/>
                    <input type="submit" name="submit" value="Add" class="field-style"/>
                </form>

            </div>
            <header class="board-head">
                <div class="centered h5">
                    <h2>Schools list</h2>
                </div>
            </header>
            <div class="board-body">
                <select class="field-style-2">
                    <?php foreach ($schools as $school) { ?>
                        <option><?php echo $school->name; ?></option>
                    <?php } ?>
                </select>
            </div>
        </section>
    </div>

<?php require_once 'includes/footer.php'; ?>
