<?php
namespace App\Models;

class Setting extends BaseModel
{
    protected string $table = 'settings';
    protected array $fillable = ['key_name', 'value', 'group_name', 'description'];

    public function getValue(string $key, mixed $default = null): mixed
    {
        $setting = $this->findOneBy('key_name', $key);
        return $setting ? $setting['value'] : $default;
    }

    public function setValue(string $key, mixed $value): void
    {
        $existing = $this->findOneBy('key_name', $key);
        if ($existing) {
            $this->update($existing['id'], ['value' => $value]);
        } else {
            $this->create(['key_name' => $key, 'value' => $value, 'group_name' => 'general']);
        }
    }

    public function getByGroup(string $group): array
    {
        return $this->findBy('group_name', $group);
    }

    public function getAllGrouped(): array
    {
        $all = $this->findAll('group_name', 'ASC');
        $grouped = [];
        foreach ($all as $s) {
            $grouped[$s['group_name']][] = $s;
        }
        return $grouped;
    }
}
