// Add Setting Routes to routes/web.php
$router->get('/settings', [\App\Controllers\SettingController::class, 'index'], ['auth', 'role:admin']);
$router->post('/settings/update', [\App\Controllers\SettingController::class, 'update'], ['auth', 'role:admin']);

// Add Error route mapping if needed (Router usually handles this automatically by looking for ErrorController)
