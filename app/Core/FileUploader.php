<?php
namespace App\Core;

/**
 * ===========================================
 * Secure File Uploader
 * ===========================================
 * Handle file uploads secara aman:
 * - Validate file type & size
 * - Generate unique filename
 * - Move to storage directory
 */
class FileUploader
{
    private string $uploadDir;
    private array  $allowedTypes = [];
    private int    $maxSize;
    private string $error = '';

    public function __construct(string $subDir = '')
    {
        $this->uploadDir = UPLOAD_PATH . ($subDir ? '/' . trim($subDir, '/') : '');
        $settingModel = new \App\Models\Setting();
        $maxMb = (int)$settingModel->getValue('max_upload_size', 10);
        $this->maxSize = $maxMb * 1024 * 1024;
        $this->allowedTypes = explode(',', ALLOWED_FILE_TYPES);
    }

    /**
     * Set allowed file types
     */
    public function setAllowedTypes(array $types): self
    {
        $this->allowedTypes = $types;
        return $this;
    }

    /**
     * Set max file size in bytes
     */
    public function setMaxSize(int $bytes): self
    {
        $this->maxSize = $bytes;
        return $this;
    }

    /**
     * Upload a file
     * 
     * @param string $inputName  The name attribute of the file input
     * @return string|false      The relative path to uploaded file, or false on failure
     */
    public function upload(string $inputName): string|false
    {
        // Check if file was uploaded
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
            $this->error = 'Tidak ada file yang dipilih.';
            return false;
        }

        $file = $_FILES[$inputName];

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->error = $this->getUploadError($file['error']);
            return false;
        }

        // Validate file size
        if ($file['size'] > $this->maxSize) {
            $maxMB = round($this->maxSize / 1048576, 1);
            $this->error = "Ukuran file melebihi batas {$maxMB} MB.";
            return false;
        }

        // Validate file extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedTypes)) {
            $this->error = "Tipe file .{$ext} tidak diizinkan. Tipe yang diizinkan: " . implode(', ', $this->allowedTypes);
            return false;
        }

        // Create upload directory if not exists
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        // Generate unique filename
        $newFilename = $this->generateFilename($ext);
        $destination = $this->uploadDir . '/' . $newFilename;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->error = 'Gagal menyimpan file.';
            return false;
        }

        // Return relative path from storage/uploads/
        $relativePath = str_replace(UPLOAD_PATH . '/', '', $destination);
        return $relativePath;
    }

    /**
     * Get error message
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * Generate unique filename
     */
    private function generateFilename(string $extension): string
    {
        return date('Ymd_His') . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    }

    /**
     * Delete an uploaded file
     */
    public static function delete(?string $relativePath): bool
    {
        if (empty($relativePath)) return false;

        $fullPath = UPLOAD_PATH . '/' . $relativePath;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    /**
     * Get readable upload error message
     */
    private function getUploadError(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE   => 'File melebihi batas upload server.',
            UPLOAD_ERR_FORM_SIZE  => 'File melebihi batas yang ditentukan.',
            UPLOAD_ERR_PARTIAL    => 'File hanya terupload sebagian.',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan.',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk.',
            UPLOAD_ERR_EXTENSION  => 'Upload dihentikan oleh ekstensi PHP.',
            default               => 'Error upload tidak diketahui.',
        };
    }
}
