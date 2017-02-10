<?php

require_once 'core/init.php';

// check if new member submitted
if (isset($_POST['submit'])) {
    // sanitize
    $sanitizer->run($_POST, [
        'name' => 'trim,sanitize',
        'email' => 'trim,email',
        'school_id' => 'trim,sanitize'

    ]);
    $sanitized = $sanitizer->getFilteredData();

    // then validate
    $validator->run($sanitized, [
        'name' => 'required,alpha_space',
        'email' => 'required,email',
        'school_id' => 'required,numeric'
    ]);
    $validated = $validator->getValidatedData();

    // check for validation errors
    if ($validator->hasErrors()) {
        $errors = $validator->getAllValidationErrors();
    } else {
        // insert into database
        $db->insert('members', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'school_id' => $validated['school_id']
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
                    <h2>Add member</h2>
                </div>
            </header>
            <div class="board-body">
                <?php if (isset($errors)) {
                    foreach ($errors as $error) { ?>
                        <div class="alert danger"><?php echo $error; ?></div>
                    <?php }
                } elseif (isset($_POST['submit'])) { ?>
                    <div class="alert success">Success! New member has been added</div>
                <?php } ?>

                <form action="member.php" method="POST">
                    <input type="text" name="name" placeholder="member name" class="field-style-2"/>
                    <input type="text" name="email" placeholder="member email" class="field-style-2"/>
                    <select name="school_id" class="field-style-2">
                        <?php foreach ($schools as $school) { ?>
                            <option value="<?php echo $school->id; ?>"><?php echo $school->name; ?></option>
                        <?php } ?>
                    </select>
                    <input type="submit" name="submit" value="Add" class="field-style"/>
                </form>
            </div>
        </section>
    </div>

<?php require_once 'includes/footer.php'; ?>
