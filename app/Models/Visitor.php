<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name', 'first_name', 'middle_initial', 'name_extension',
        'age', 'gender',
        'barangay', 'municipality', 'province',
        'contact_number',
        'purpose', 'purpose_other',
    ];

    public function getFullNameAttribute(): string
    {
        $name = $this->last_name . ', ' . $this->first_name;
        if ($this->middle_initial) $name .= ' ' . $this->middle_initial . '.';
        if ($this->name_extension) $name .= ' ' . $this->name_extension;
        return $name;
    }

    public function getPurposeLabelAttribute(): string
    {
        if ($this->purpose === 'Others' && $this->purpose_other) {
            return 'Others: ' . $this->purpose_other;
        }
        return $this->purpose ?? '—';
    }
}
