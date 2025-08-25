<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'template_content',
        'variables',
        'type',
        'is_active',
        'description'
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrderNotification($query)
    {
        return $query->where('type', 'order_notification');
    }

    public function scopeMarketing($query)
    {
        return $query->where('type', 'marketing');
    }

    public function scopeCustom($query)
    {
        return $query->where('type', 'custom');
    }

    public function getVariablesListAttribute()
    {
        return $this->variables ?? [];
    }

    public function replaceVariables($data)
    {
        $content = $this->template_content;
        
        foreach ($data as $key => $value) {
            $content = str_replace('{' . $key . '}', $value, $content);
        }
        
        return $content;
    }

    public function validateVariables($data)
    {
        $requiredVariables = $this->variables ?? [];
        $missingVariables = [];
        
        foreach ($requiredVariables as $variable) {
            if (!isset($data[$variable]) || empty($data[$variable])) {
                $missingVariables[] = $variable;
            }
        }
        
        return $missingVariables;
    }
}
