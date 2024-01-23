<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
define("BASE_PUBLIC_ADDRESS", "$protocol://$host" . "/");

require_once("../private/index.php");
require_once(PRIVATE_ROOT_PATH . "classes/TaskManager.php");

?><!DOCTYPE html>
<html lang="en">
<?php require_once(PRIVATE_ROOT_PATH . "templates/head.tpl") ?>
<body>
<?php require_once(PRIVATE_ROOT_PATH . "templates/task_template.tpl") ?>
<div id="root">
    <?php require_once(PRIVATE_ROOT_PATH . "templates/task_registration_form.tpl") ?>
    <?php require_once(PRIVATE_ROOT_PATH . "templates/task_list.tpl") ?>
</div>
<script src="<?= BASE_PUBLIC_ADDRESS . 'scripts/index.js' ?>"></script>
</body>
</html>