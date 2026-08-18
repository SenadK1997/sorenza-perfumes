<x-filament-panels::page>
    <div class="space-y-4">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:bg-gray-900 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs uppercase tracking-widest text-gray-400">Kupac</div>
                    <div class="text-lg font-semibold">{{ $this->record->customer?->full_name ?? '—' }}</div>
                    <div class="text-xs text-gray-500">{{ $this->record->customer?->email ?? '' }}</div>
                </div>
                <div class="text-right text-xs text-gray-500">
                    <div>Poruka: {{ $this->record->messages()->count() }}</div>
                    <div>Zadnja: {{ $this->record->last_message_at?->diffForHumans() ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="max-h-[60vh] overflow-y-auto rounded-xl border border-gray-200 bg-gray-50 p-4 space-y-3 dark:bg-gray-900 dark:border-gray-700">
            @forelse($this->getThreadMessages() as $msg)
                @php
                    $isAdmin = $msg->direction === 'admin';
                    $align   = $isAdmin ? 'text-right' : 'text-left';
                    $bubble  = $isAdmin
                        ? 'bg-primary-600 text-white'
                        : 'bg-white text-gray-900 border border-gray-200 dark:bg-gray-800 dark:text-gray-100';
                @endphp
                <div class="{{ $align }}">
                    <div class="text-[10px] uppercase tracking-widest text-gray-400 mb-1 px-1">
                        @if($isAdmin)
                            Sorenza
                            @if($msg->author)
                                <span class="text-gray-400/70 normal-case tracking-normal">· interno: {{ $msg->author->name }}</span>
                            @endif
                            @if($msg->is_broadcast)
                                <span class="ml-1 rounded-full bg-amber-100 text-amber-700 px-1.5 py-0.5 text-[9px] tracking-wider normal-case">broadcast</span>
                            @endif
                        @else
                            {{ $this->record->customer?->full_name ?? 'Kupac' }}
                        @endif
                        · {{ $msg->created_at->format('d.m.Y H:i') }}
                    </div>
                    <div class="{{ $bubble }} inline-block !text-left rounded-lg px-3 py-1.5 text-base leading-snug whitespace-pre-wrap break-words align-top max-w-[80%] sm:max-w-[60%]">{{ trim($msg->body) }}</div>
                    <div>
                        <button wire:click="deleteMessage({{ $msg->id }})"
                                wire:confirm="Obrisati ovu poruku?"
                                class="mt-1 text-[10px] text-gray-400 hover:text-red-600 transition px-1">
                            obriši
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-center text-sm text-gray-500 py-6">Nema poruka u ovom razgovoru.</p>
            @endforelse
        </div>

        <form wire:submit="send" class="space-y-4">
            {{ $this->form }}
            <div class="flex justify-end">
                <x-filament::button type="submit" icon="heroicon-o-paper-airplane">Pošalji</x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
