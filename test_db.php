<?php
require __DIR__ . '/app/Config/config.php';
$db = \App\Config\Database::getConnection();
$students = $db->query("SELECT e.*, u.name, u.email, u.nim_nidn, u.avatar
                    FROM enrollments e
                    JOIN users u ON e.user_id = u.id
                    WHERE e.course_id = 1 AND e.status = 'active'
                    ORDER BY u.name")->fetchAll();
var_dump($students);
