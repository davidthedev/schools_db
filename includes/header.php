<!DOCTYPE html>
<html>
<head>
    <title>Toucan DB</title>
    <link href="<?php echo BASE_URL . '/../css/stylesheet.css'; ?>" rel="stylesheet" type="text/css"/>
</head>
<body class="main">
    <div class="site-wrapper main">
        <header class="site-header">
            <div class="container cf">
                <nav>
                    <ul class="nav">
                        <li><a href="member.php" <?php echo preg_match('/(member)/', CURRENT_URL) ? 'class="active"' : ''?>>Add member</a></li>
                        <li><a href="add-school.php" <?php echo preg_match('/(add-school)/', CURRENT_URL) ? 'class="active"' : ''?>>Add school</a></li>
                        <li><a href="view.php" <?php echo preg_match('/(view)/', CURRENT_URL) ? 'class="active"' : ''?>>View members</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <div class="site-content cf">
