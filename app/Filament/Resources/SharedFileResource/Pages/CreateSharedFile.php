<?php

namespace App\Filament\Resources\SharedFileResource\Pages;

use App\Filament\Resources\SharedFileResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateSharedFile extends CreateRecord
{
    protected static string $resource = SharedFileResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uploaded_by'] = Auth::id();

        // Fill metadata if the FileUpload afterStateUpdated hook didn't fire
        if (! empty($data['path']) && empty($data['size_bytes'])) {
            $full = Storage::disk('public')->path($data['path']);
            if (is_file($full)) {
                $data['size_bytes']    = filesize($full);
                $data['mime_type']     = mime_content_type($full) ?: null;
                $data['original_name'] = basename($data['path']);
            }
        }

        return $data;
    }
}
