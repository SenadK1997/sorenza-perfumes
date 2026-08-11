<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SharedFile extends Model
{
    protected $fillable = [
        'title', 'category', 'description',
        'path', 'original_name', 'mime_type', 'size_bytes', 'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /** Public URL (via storage:link). */
    public function getUrlAttribute(): ?string
    {
        return $this->path ? Storage::url($this->path) : null;
    }

    /** Human-readable size. */
    public function getSizeHumanAttribute(): string
    {
        $b = (int) $this->size_bytes;
        if ($b <= 0) return '—';
        if ($b < 1024) return $b . ' B';
        if ($b < 1024 * 1024) return round($b / 1024, 1) . ' KB';
        if ($b < 1024 * 1024 * 1024) return round($b / 1024 / 1024, 1) . ' MB';
        return round($b / 1024 / 1024 / 1024, 2) . ' GB';
    }

    public function getIconAttribute(): string
    {
        $ext = strtolower(pathinfo($this->original_name ?: $this->path, PATHINFO_EXTENSION));
        return match ($ext) {
            'pdf'                 => 'heroicon-o-document-text',
            'xls', 'xlsx', 'csv'  => 'heroicon-o-table-cells',
            'doc', 'docx'         => 'heroicon-o-document',
            'zip', 'rar', '7z'    => 'heroicon-o-archive-box',
            'jpg','jpeg','png','webp','gif' => 'heroicon-o-photo',
            default               => 'heroicon-o-paper-clip',
        };
    }
}
