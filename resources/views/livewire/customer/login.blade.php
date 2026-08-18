<div class="min-h-[80vh] px-4 py-12 sm:py-16"
     style="background:
        radial-gradient(circle at 10% 10%, rgba(255,153,180,0.28) 0%, transparent 45%),
        radial-gradient(circle at 90% 20%, rgba(180,150,255,0.28) 0%, transparent 45%),
        radial-gradient(circle at 50% 90%, rgba(140,190,255,0.28) 0%, transparent 45%),
        linear-gradient(135deg, #fdf7fb 0%, #f4f0ff 50%, #eef7fb 100%);
        background-attachment: fixed;">
    <div class="mx-auto max-w-6xl">

        <div class="text-center mb-10">
            <div class="text-[10px] tracking-[0.45em] uppercase text-amber-700 font-light">Sorénza</div>
            <h1 class="mt-2 font-serif italic font-light text-3xl sm:text-4xl text-gray-900">Moj nalog</h1>
            <p class="mt-2 text-sm text-gray-500">Prijavite se emailom da otključate pogodnosti i pratite kupovine.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- LEFT: benefits --}}
            <div class="space-y-6">
                {{-- Loyalty pitch --}}
                <div class="rounded-3xl bg-white/80 backdrop-blur border border-white/90 shadow-xl p-6 sm:p-7">
                    <div class="flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l2.4 5 5.6.8-4 3.9.9 5.6L12 14.9 7.1 17.3l.9-5.6-4-3.9 5.6-.8L12 2z"/>
                        </svg>
                        <h2 class="font-serif italic text-xl text-gray-900">Loyalty pogodnosti</h2>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed">
                        Svaki prijavljeni kupac dobija automatski popust na svaku kupovinu. Što više kupujete tokom godine, prelazite na viši nivo i dobijate veći popust.
                    </p>

                    <div class="mt-5 grid grid-cols-5 gap-2">
                        @php
                            $tiers = App\Services\CustomerLoyalty::TIERS;
                        @endphp
                        @foreach($tiers as $t)
                            <div class="rounded-xl border border-gray-200 bg-white/70 p-3 text-center">
                                <div class="text-[10px] font-semibold uppercase tracking-widest" style="color: {{ $t['accent'] }};">
                                    {{ $t['name'] }}
                                </div>
                                <div class="mt-2 inline-flex items-center rounded-full text-white text-[10px] font-bold px-2 py-0.5"
                                     style="background: {{ $t['accent'] }};">
                                    −{{ (int) $t['discount'] }}%
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-4 text-[11px] text-gray-500 text-center leading-relaxed">
                        Prijavite se da vidite vaš trenutni nivo, koliko ste potrošili ove godine i koliko vam nedostaje do sljedećeg nivoa.
                    </p>
                </div>

                {{-- Other benefits --}}
                <div class="rounded-3xl bg-white/80 backdrop-blur border border-white/90 shadow-xl p-6 sm:p-7">
                    <h3 class="font-serif italic text-xl text-gray-900 mb-5">Šta dobijate s nalogom</h3>

                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <div class="mt-0.5 h-9 w-9 shrink-0 rounded-xl bg-gradient-to-br from-amber-100 to-rose-100 flex items-center justify-center text-amber-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5h6M9 3v18M15 3v18M3 8h18M3 16h18"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Pregled svih narudžbi</div>
                                <div class="text-xs text-gray-500 mt-0.5">Online kupovine i direktne prodaje — sve na jednom mjestu, sa statusima i detaljima.</div>
                            </div>
                        </li>

                        <li class="flex items-start gap-3">
                            <div class="mt-0.5 h-9 w-9 shrink-0 rounded-xl bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center text-emerald-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6M4 10a8 8 0 0 1 14.6-4.5M20 14a8 8 0 0 1-14.6 4.5"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Kupi ponovo jednim klikom</div>
                                <div class="text-xs text-gray-500 mt-0.5">Vratite se omiljenim mirisima — svaki artikal iz prethodnih narudžbi je jedan klik od korpe.</div>
                            </div>
                        </li>

                        <li class="flex items-start gap-3">
                            <div class="mt-0.5 h-9 w-9 shrink-0 rounded-xl bg-gradient-to-br from-fuchsia-100 to-violet-100 flex items-center justify-center text-fuchsia-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6l2.1 2.1M5.6 18.4l2.1-2.1m8.6-8.6l2.1-2.1"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Vaš mirisni profil</div>
                                <div class="text-xs text-gray-500 mt-0.5">Automatska analiza — omiljene note, inspiracije i preporuke prilagođene vašem ukusu.</div>
                            </div>
                        </li>

                        <li class="flex items-start gap-3">
                            <div class="mt-0.5 h-9 w-9 shrink-0 rounded-xl bg-gradient-to-br from-sky-100 to-blue-100 flex items-center justify-center text-sky-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M12 3l8 4v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Brža kasa</div>
                                <div class="text-xs text-gray-500 mt-0.5">Vaša adresa se čuva i automatski popunjava kod sljedeće narudžbe — nema više dvostrukog kucanja.</div>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Reminder --}}
                <div class="rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50/80 to-rose-50/60 px-5 py-4 flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-amber-700 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M4.93 4.93a10 10 0 1 0 14.14 0 10 10 0 0 0-14.14 0z"/>
                    </svg>
                    <div class="text-xs text-amber-900 leading-relaxed space-y-2">
                        <p><strong>Kako funkcioniše prijava:</strong></p>
                        <ol class="list-decimal ml-5 space-y-1">
                            <li>Nakon vaše prve narudžbe (online ili kod prodavača uživo), nalog je spreman.</li>
                            <li>Unesite ovdje email koji ste koristili prilikom kupovine.</li>
                            <li>Poslat ćemo vam sigurni link za prijavu na taj email — kliknete i odmah ste unutra. Ako želite, možete kasnije u nalogu postaviti lozinku za bržu prijavu.</li>
                        </ol>
                        <p class="text-[11px] text-amber-800/80 pt-1">Nema klasične registracije, forme za popunjavanje ili čekanja odobrenja.</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: login form --}}
            <div>
                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50/70 px-4 py-3 text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @php
                    // Remember last used login method (cookie set on successful password login).
                    // Errors from a password submit also override the default to keep the user on that tab.
                    $lastMethod = request()->cookie('sorenza_last_login', 'magic');
                    if (session()->has('errors') && old('password')) {
                        $lastMethod = 'password';
                    }
                @endphp

                <div x-data="{
                        tab: (localStorage.getItem('sorenza_login_tab') || '{{ $lastMethod }}'),
                        setTab(t) { this.tab = t; try { localStorage.setItem('sorenza_login_tab', t); } catch(e) {} }
                     }"
                     class="rounded-3xl bg-white/85 backdrop-blur border border-white/90 shadow-xl p-6 sm:p-8 lg:sticky lg:top-24">
                    <div class="flex gap-2 mb-6 rounded-full bg-gray-100 p-1">
                        <button type="button" @click="setTab('password')"
                                :class="tab==='password' ? 'bg-white shadow text-amber-800' : 'text-gray-500'"
                                class="flex-1 rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] transition">
                            Lozinka
                        </button>
                        <button type="button" @click="setTab('magic')"
                                :class="tab==='magic' ? 'bg-white shadow text-amber-800' : 'text-gray-500'"
                                class="flex-1 rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] transition">
                            Link na email
                        </button>
                    </div>

                    {{-- PASSWORD --}}
                    <form x-show="tab==='password'" x-cloak method="POST" action="{{ route('customer.login.password') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="email-pw" class="block text-xs font-medium uppercase tracking-[0.2em] text-gray-500 mb-1.5">Email</label>
                            <input id="email-pw" name="email" type="email" required autocomplete="email"
                                   value="{{ old('email') }}"
                                   class="w-full rounded-full border-amber-200/80 bg-white/80 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        </div>
                        <div>
                            <label for="password-pw" class="block text-xs font-medium uppercase tracking-[0.2em] text-gray-500 mb-1.5">Lozinka</label>
                            <input id="password-pw" name="password" type="password" required autocomplete="current-password"
                                   class="w-full rounded-full border-amber-200/80 bg-white/80 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                            @error('email') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit"
                                class="w-full rounded-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] px-6 py-3 text-xs font-semibold uppercase tracking-[0.28em] text-white shadow-lg hover:opacity-95 transition">
                            Prijavi me
                        </button>
                        <p class="text-[11px] text-gray-500 leading-relaxed text-center">
                            Zaboravili lozinku ili je još niste postavili?
                            <button type="button" @click="setTab('magic')" class="text-amber-800 font-semibold underline underline-offset-2 hover:text-amber-700">
                                Prijavite se linkom na email
                            </button>
                        </p>
                    </form>

                    {{-- MAGIC LINK --}}
                    <form x-show="tab==='magic'" method="POST" action="{{ route('customer.login.send') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="email-magic" class="block text-xs font-medium uppercase tracking-[0.2em] text-gray-500 mb-1.5">Email adresa naloga</label>
                            <input id="email-magic" name="email" type="email" required autocomplete="email"
                                   value="{{ old('email') }}"
                                   placeholder="vas@email.com"
                                   class="w-full rounded-full border-amber-200/80 bg-white/80 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                            @error('email') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit"
                                class="w-full rounded-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] px-6 py-3 text-xs font-semibold uppercase tracking-[0.28em] text-white shadow-lg hover:opacity-95 transition">
                            Pošalji mi link
                        </button>
                        <p class="text-[11px] text-gray-500 leading-relaxed text-center">
                            Poslat ćemo vam sigurni link za jednokratnu prijavu. Nakon prijave možete postaviti lozinku za bržu sljedeću prijavu.
                            <br>
                            <button type="button" @click="setTab('password')" class="mt-1 text-amber-800 font-semibold underline underline-offset-2 hover:text-amber-700">
                                Već imate lozinku? Prijavite se lozinkom
                            </button>
                        </p>
                    </form>

                    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                        <p class="text-xs text-gray-500">
                            Još nemate nalog?
                        </p>
                        <a href="{{ route('shop') }}"
                           class="mt-2 inline-flex items-center gap-2 rounded-full border border-amber-800/60 bg-white px-5 py-2 text-[10px] font-semibold uppercase tracking-[0.25em] text-amber-900 hover:bg-gradient-to-r hover:from-[#BBA14F] hover:to-[#DBC584] hover:text-white hover:border-transparent transition">
                            Otkrijte kolekciju
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <p class="mt-3 text-[10px] text-gray-400 uppercase tracking-widest">
                            Nalog vam kreiramo nakon prve kupovine — nema forme za registraciju
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
