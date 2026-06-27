<?php
namespace App\Controllers;

use App\Models\Setting;

class SettingController extends BaseController
{
    private Setting $settingModel;

    public function __construct() { $this->settingModel = new Setting(); }

    /** GET /settings */
    public function index(): void
    {
        $this->setTitle('Pengaturan Sistem');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Pengaturan']]);
        
        $settings = $this->settingModel->getAllGrouped();
        $this->render('settings/index', ['pageTitle' => 'Pengaturan Sistem', 'settings' => $settings]);
    }

    /** POST /settings/update */
    public function update(): void
    {
        $this->validateCSRF();
        $data = $this->allInput();

        // Loop through POST data to update keys
        foreach ($data as $key => $value) {
            if ($key !== '_csrf_token') {
                $this->settingModel->setValue($key, $value);
            }
        }

        flash_success('Pengaturan berhasil diperbarui.');
        $this->back();
    }
}
