<?php
namespace App\Core;

/**
 * ===========================================
 * Input Validator
 * ===========================================
 * Validasi input dengan rules yang fleksibel.
 * Mendukung: required, email, min, max, numeric, unique, in, file validations.
 */
class Validator
{
    private array $errors = [];
    private array $data   = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validate data against rules
     * 
     * @param array $rules ['field' => 'required|email|min:6|max:255']
     * @return bool True if validation passes
     */
    public function validate(array $rules): bool
    {
        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;
            $label = $this->humanize($field);

            foreach ($rulesArray as $rule) {
                $param = null;
                if (str_contains($rule, ':')) {
                    [$rule, $param] = explode(':', $rule, 2);
                }

                $methodName = 'rule' . ucfirst($rule);
                if (method_exists($this, $methodName)) {
                    $this->$methodName($field, $value, $label, $param);
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Get validation errors
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get first error message
     */
    public function firstError(): string
    {
        $first = reset($this->errors);
        return $first ?: '';
    }

    /**
     * Add error for a field
     */
    private function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    /**
     * Convert field name to human-readable label
     */
    private function humanize(string $field): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $field));
    }

    // ===========================================
    // Validation Rules
    // ===========================================

    private function ruleRequired(string $field, mixed $value, string $label, ?string $param): void
    {
        if ($value === null || $value === '' || $value === []) {
            $this->addError($field, "{$label} wajib diisi.");
        }
    }

    private function ruleEmail(string $field, mixed $value, string $label, ?string $param): void
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "{$label} harus berupa alamat email yang valid.");
        }
    }

    private function ruleMin(string $field, mixed $value, string $label, ?string $param): void
    {
        if (!empty($value) && strlen((string)$value) < (int)$param) {
            $this->addError($field, "{$label} minimal {$param} karakter.");
        }
    }

    private function ruleMax(string $field, mixed $value, string $label, ?string $param): void
    {
        if (!empty($value) && strlen((string)$value) > (int)$param) {
            $this->addError($field, "{$label} maksimal {$param} karakter.");
        }
    }

    private function ruleNumeric(string $field, mixed $value, string $label, ?string $param): void
    {
        if (!empty($value) && !is_numeric($value)) {
            $this->addError($field, "{$label} harus berupa angka.");
        }
    }

    private function ruleInteger(string $field, mixed $value, string $label, ?string $param): void
    {
        if (!empty($value) && filter_var($value, FILTER_VALIDATE_INT) === false) {
            $this->addError($field, "{$label} harus berupa bilangan bulat.");
        }
    }

    private function ruleIn(string $field, mixed $value, string $label, ?string $param): void
    {
        $allowed = explode(',', $param);
        if (!empty($value) && !in_array($value, $allowed, true)) {
            $this->addError($field, "{$label} harus salah satu dari: " . implode(', ', $allowed) . ".");
        }
    }

    private function ruleUnique(string $field, mixed $value, string $label, ?string $param): void
    {
        if (empty($value)) return;

        // Format: table,column,exceptId
        $parts = explode(',', $param);
        $table = $parts[0];
        $column = $parts[1] ?? $field;
        $exceptId = $parts[2] ?? null;

        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = ?";
        $params = [$value];

        if ($exceptId) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }

        $count = $db->query($sql, $params)->fetchColumn();
        if ($count > 0) {
            $this->addError($field, "{$label} sudah digunakan.");
        }
    }

    private function ruleConfirmed(string $field, mixed $value, string $label, ?string $param): void
    {
        $confirmField = $field . '_confirmation';
        $confirmValue = $this->data[$confirmField] ?? null;
        if ($value !== $confirmValue) {
            $this->addError($field, "Konfirmasi {$label} tidak cocok.");
        }
    }

    private function ruleFile(string $field, mixed $value, string $label, ?string $param): void
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return; // Tidak wajib kecuali ada rule 'required'
        }
        if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            $this->addError($field, "Upload {$label} gagal.");
        }
    }

    private function ruleMaxfile(string $field, mixed $value, string $label, ?string $param): void
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return;
        }
        $maxBytes = (int)$param;
        if ($_FILES[$field]['size'] > $maxBytes) {
            $maxMB = round($maxBytes / 1048576, 1);
            $this->addError($field, "Ukuran {$label} maksimal {$maxMB} MB.");
        }
    }

    private function ruleMimes(string $field, mixed $value, string $label, ?string $param): void
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return;
        }
        $allowed = explode(',', $param);
        $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $this->addError($field, "{$label} harus berformat: " . implode(', ', $allowed) . ".");
        }
    }
}
