<div class="min-h-[80vh] px-4 py-10"
     style="background:
        radial-gradient(circle at 10% 10%, rgba(255,153,180,0.28) 0%, transparent 45%),
        radial-gradient(circle at 90% 20%, rgba(180,150,255,0.28) 0%, transparent 45%),
        linear-gradient(135deg, #fdf7fb 0%, #f4f0ff 50%, #eef7fb 100%);
        background-attachment: fixed;">
    <div class="mx-auto max-w-3xl">
        @include('livewire.customer.partials.header', ['customer' => $customer])

        @if(session('status'))
            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50/70 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-8 rounded-3xl bg-white/85 backdrop-blur border border-white/90 shadow-xl overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-amber-100 bg-gradient-to-r from-amber-50/60 to-rose-50/40 flex items-center gap-3">
                <div class="h-10 w-10 rounded-full flex items-center justify-center text-white shadow"
                     style="background: linear-gradient(135deg,#8b6914,#BBA14F,#DBC584);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l2.4 5 5.6.8-4 3.9.9 5.6L12 14.9 7.1 17.3l.9-5.6-4-3.9 5.6-.8L12 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[10px] uppercase tracking-[0.3em] text-amber-700">Razgovor sa</div>
                    <div class="font-serif italic text-lg text-gray-900">Sorenza</div>
                </div>
            </div>

            {{-- Messages --}}
            <div class="max-h-[55vh] overflow-y-auto px-4 sm:px-6 py-6 space-y-3 bg-gradient-to-b from-white/40 to-white/70">
                @forelse($messages as $msg)
                    @php
                        $isAdmin = $msg->direction === 'admin';
                        $wrap    = $isAdmin ? 'text-left' : 'text-right';
                        $bubble  = $isAdmin
                            ? 'bg-white text-gray-900 border border-amber-100 shadow-sm'
                            : 'bg-gradient-to-br from-[#8b6914] via-[#BBA14F] to-[#DBC584] text-white shadow';
                    @endphp
                    <div class="{{ $wrap }}">
                        <div class="text-[10px] uppercase tracking-widest text-gray-400 mb-1 px-1">
                            @if($isAdmin)
                                Sorenza
                                @if($msg->is_broadcast)
                                    <span class="ml-1 rounded-full bg-amber-100 text-amber-800 px-1.5 py-0.5 text-[9px] tracking-wider normal-case">obavještenje</span>
                                @endif
                            @else
                                Vi
                            @endif
                            · {{ $msg->created_at->format('d.m.Y H:i') }}
                        </div>
                        <div class="{{ $bubble }} inline-block !text-left rounded-lg px-3 py-1.5 text-base leading-snug whitespace-pre-wrap break-words align-top max-w-[80%] sm:max-w-[60%]">{{ trim($msg->body) }}</div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-sm text-gray-500">Još nema poruka. Slobodno pošaljite pitanje ili komentar — javit ćemo se u najkraćem roku.</p>
                    </div>
                @endforelse
            </div>

            {{-- Reply form --}}
            <form wire:submit="send" class="border-t border-amber-100 bg-white/80 px-4 sm:px-6 py-4 space-y-3">
                <label for="msg-body" class="text-[10px] uppercase tracking-[0.3em] text-gray-500 font-medium">
                    Vaša poruka
                </label>
                <textarea id="msg-body" wire:model="body" rows="3"
                          class="block w-full rounded-2xl border-amber-200/80 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-amber-500 focus:ring-amber-500 resize-none"
                          placeholder="Napišite svoju poruku..."></textarea>
                @error('body') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.24em] text-white shadow hover:opacity-95 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Pošalji
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
