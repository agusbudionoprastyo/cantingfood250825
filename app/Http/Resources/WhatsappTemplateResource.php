<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WhatsappTemplateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'template_content' => $this->template_content,
            'variables' => $this->variables ?? [],
            'variables_list' => $this->variables_list,
            'type' => $this->type,
            'type_label' => $this->getTypeLabel(),
            'is_active' => (bool) $this->is_active,
            'description' => $this->description,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'usage_count' => $this->getUsageCount(),
            'last_used' => $this->getLastUsedDate(),
        ];
    }

    private function getTypeLabel()
    {
        return match($this->type) {
            'order_notification' => 'Order Notification',
            'custom' => 'Custom Template',
            'marketing' => 'Marketing Template',
            default => 'Unknown'
        };
    }

    private function getUsageCount()
    {
        return \App\Models\WhatsappLog::where('metadata->template_used', $this->slug)->count();
    }

    private function getLastUsedDate()
    {
        $lastLog = \App\Models\WhatsappLog::where('metadata->template_used', $this->slug)
            ->orderBy('created_at', 'desc')
            ->first();
            
        return $lastLog?->created_at?->format('Y-m-d H:i:s');
    }
}
