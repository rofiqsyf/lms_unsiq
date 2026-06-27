<?php
require __DIR__ . '/vendor/autoload.php';
session_start();

$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'admin';

require __DIR__ . '/app/Config/config.php';
require __DIR__ . '/app/Helpers/functions.php';

$db = \App\Config\Database::getConnection();
$courseId = $db->query("SELECT id FROM courses LIMIT 1")->fetchColumn();

if ($courseId) {
    $controller = new \App\Controllers\CourseController();
    $controller->show($courseId);
} else {
    echo "No courses found";
}
