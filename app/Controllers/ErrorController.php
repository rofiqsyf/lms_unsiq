<?php
namespace App\Controllers;

class ErrorController extends BaseController
{
    public function notFound(): void
    {
        http_response_code(404);
        $this->render('errors/404', [], null); // No layout
    }

    public function forbidden(): void
    {
        http_response_code(403);
        $this->render('errors/403', [], null); // No layout
    }

    public function serverError(): void
    {
        http_response_code(500);
        $this->render('errors/500', [], null); // No layout
    }
}
