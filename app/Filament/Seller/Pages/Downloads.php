<?php

namespace App\Filament\Seller\Pages;

use App\Models\SharedFile;
use Filament\Pages\Page;

class Downloads extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-folder-arrow-down';
    protected static ?string $navigationLabel = 'Preuzimanja';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?string $title           = 'Preuzimanja — Katalozi & fajlovi';
    protected static ?int $navigationSort     = 20;
    protected static string $view             = 'filament.seller.pages.downloads';

    public string $category = 'all';

    public function setCategory(string $c): void
    {
        $this->category = $c;
    }

    public function getViewData(): array
    {
        $files = SharedFile::query()
            ->when($this->category !== 'all', fn ($q) => $q->where('category', $this->category))
            ->latest()
            ->get();

        return [
            'files'      => $files,
            'categories' => [
                'all'       => 'Sve',
                'catalog'   => 'Katalozi',
                'price'     => 'Cjenovnici',
                'guide'     => 'Uputstva',
                'marketing' => 'Marketing',
                'other'     => 'Ostalo',
            ],
        ];
    }
}
