<?php

require_once 'core/init.php';

// get selected school id
$schoolId = isset($_GET['school']) ? $_GET['school'] : 0;
$perPage = isset($_GET['per-page']) ? $_GET['per-page'] : 10;
$currentPage = !empty($_GET['page']) ? $_GET['page'] : 1;

// start pagination
$paginator->setRowsPerPage($perPage);
$paginator->setCurrentPage($currentPage);

// add limit and offset if necessary
if ($paginator->getRowsPerPage() == 0) {
    $limit = '';
    $offset = '';
} else {
    $limit = "LIMIT {$paginator->getRowsPerPage()}";
    $offset = "OFFSET {$paginator->getSearchOffset()}";
}

// begin sql statement
$sql = "SELECT members.id, members.email, members.name, schools.name AS school FROM `members`";
$sql .= " INNER JOIN `schools` on members.school_id = schools.id";

// display all members or members of certain schools only depending on what user selects
if ($schoolId != 'view-all') {
    $paginator->setTotalRowsFromDb($db->select('members', '*', [
        'school_id' => $schoolId
    ])->count());
    $sql .= " WHERE schools.id = $schoolId";
} else {
    $paginator->setTotalRowsFromDb($db->select('members')->count());
}

$sql .= " ORDER BY members.id DESC $limit $offset";

$totalPages = $paginator->getTotalPages();

// get members
$db->setFetchStyle('object');
$members = $db->query($sql)->fetchAll();

// get all schools
$db->select('schools');
$schools = $db->fetchAll();

?>

<?php require_once 'includes/header.php'; ?>

    <div class="container">
        <section class="board">
            <header class="board-head">
                <div class="centered h5">
                    <h2>View members</h2>
                </div>
            </header>
            <div class="board-body">
                <form method="GET" action="view.php">
                    <div class="filters">
                        <label>School </label>
                        <select name="school" class="field-style-2">
                            <option value="view-all" selected>All schools</option>
                            <?php foreach ($schools as $school) { ?>
                                <option value="<?php echo $school->id; ?>"<?php echo $schoolId == $school->id ? ' selected' : ''; ?>><?php echo $school->name; ?></option>
                            <?php } ?>
                        </select>
                        <label>View per page </label>
                        <select name="per-page" class="field-style-2">
                            <option value="0"<?php echo $perPage == '0' ? ' selected' : ''; ?>>View all</option>
                            <option value="10"<?php echo $perPage == '10' ? ' selected' : ''; ?>>10</option>
                            <option value="20"<?php echo $perPage == '20' ? ' selected' : ''; ?>>20</option>
                        </select>
                        <input type="submit" name="submit" value="View" class="field-style"/>
                    </div>

                    <?php if(!empty($members)) { ?>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>School</th>
                            </tr>
                            <?php foreach ($members as $member) { ?>
                                <tr>
                                    <td><?php echo $member->id; ?></td>
                                    <td><?php echo $member->name; ?></td>
                                    <td><?php echo $member->email; ?></td>
                                    <td><?php echo $member->school; ?></td>
                                </tr>
                            <?php }  ?>
                        </table>
                    <?php } else { ?>
                        <h3>Sorry, no members yet</h3>
                    <?php } ?>

                    <?php if ($totalPages > 1) : ?>
                        <div class="pagination">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <input type="submit" name="page" value="<?php echo $i; ?>"  class="field-style <?php echo $currentPage == $i ? 'active-page' : ''?>">
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </section>
    </div>

<?php require_once 'includes/footer.php'; ?>
