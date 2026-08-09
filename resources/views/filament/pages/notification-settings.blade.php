<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap gap-2 justify-end">
            <x-filament::button
                type="button"
                color="gray"
                icon="heroicon-o-paper-airplane"
                wire:click="sendTestTelegram">
                Pošalji test poruku
            </x-filament::button>

            <x-filament::button type="submit" icon="heroicon-o-check">
                Sačuvaj postavke
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
