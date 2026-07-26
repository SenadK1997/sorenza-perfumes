<div class="py-16 sm:py-24 relative">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 relative z-10">
      <div class="relative isolate overflow-hidden px-6 py-24 sm:rounded-3xl sm:px-16 xl:py-32 ring-1 ring-white/10 shadow-2xl"
           style="background:
              radial-gradient(circle at 15% 20%, rgba(217,119,87,0.35) 0%, transparent 55%),
              radial-gradient(circle at 85% 80%, rgba(244,208,145,0.28) 0%, transparent 55%),
              linear-gradient(160deg, #0f0f1e 0%, #1c1830 50%, #0a0a15 100%);">

        {{-- Animated gold glow orbs --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-amber-500/15 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-rose-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>

        {{-- Vertical hair-lines --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
          <div class="absolute top-0 left-1/4 w-px h-full bg-gradient-to-b from-transparent via-amber-200/10 to-transparent"></div>
          <div class="absolute top-0 right-1/4 w-px h-full bg-gradient-to-b from-transparent via-amber-200/10 to-transparent"></div>
        </div>

        {{-- Ornamental corner brackets --}}
        <div aria-hidden="true" class="pointer-events-none absolute inset-8">
            <span class="absolute top-0 left-0 h-6 w-6 border-t border-l border-amber-200/30"></span>
            <span class="absolute top-0 right-0 h-6 w-6 border-t border-r border-amber-200/30"></span>
            <span class="absolute bottom-0 left-0 h-6 w-6 border-b border-l border-amber-200/30"></span>
            <span class="absolute bottom-0 right-0 h-6 w-6 border-b border-r border-amber-200/30"></span>
        </div>

        {{-- Content --}}
        <div class="relative z-10">
          <div class="flex items-center justify-center gap-3 mb-4">
              <span class="h-px w-10 bg-gradient-to-r from-transparent to-amber-300/70"></span>
              <span class="text-[10px] sm:text-xs tracking-[0.45em] uppercase text-amber-200/90 font-light">Ekskluzivna prilika</span>
              <span class="h-px w-10 bg-gradient-to-l from-transparent to-amber-300/70"></span>
          </div>

          <h2 class="mx-auto max-w-3xl text-center font-serif italic font-light text-4xl sm:text-5xl lg:text-6xl tracking-tight">
            <span class="bg-[linear-gradient(110deg,#ffffff_0%,#f4d091_45%,#ffffff_50%,#f4d091_55%,#e8c789_100%)] bg-[length:200%_100%] bg-clip-text text-transparent">
                Postani naš partner
            </span>
          </h2>

          <div class="mx-auto mt-6 flex items-center justify-center gap-2">
              <span class="h-px w-16 bg-gradient-to-r from-transparent to-amber-400/70"></span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-amber-300" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2l2 6 6 2-6 2-2 6-2-6-6-2 6-2z"/>
              </svg>
              <span class="h-px w-16 bg-gradient-to-l from-transparent to-amber-400/70"></span>
          </div>

          <p class="mx-auto mt-8 max-w-xl text-center text-base sm:text-lg leading-relaxed text-gray-300/90">
            Prijavi se i otkrij mogućnosti saradnje s našim timom.
            <span class="text-amber-200/90 italic">Zajedno stvaramo nešto izvanredno.</span>
          </p>

          <div x-data="{ submitted: false }">
            <template x-if="submitted">
                <div class="mt-12 mx-auto max-w-lg text-center p-8 rounded-2xl border border-amber-300/20 bg-white/5 backdrop-blur-sm animate-fade-in">
                    <div class="mx-auto mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-amber-400/30 to-rose-400/30 ring-1 ring-amber-200/40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="font-serif italic text-2xl text-white mb-1">Hvala na prijavi!</h3>
                    <p class="text-sm text-gray-400">Kontaktiraćemo vas uskoro.</p>
                </div>
            </template>

            <form
                x-show="!submitted"
                @submit.prevent="submitted = true"
                class="mx-auto mt-10 flex max-w-lg flex-col sm:flex-row gap-3"
            >
                <label for="email-address" class="sr-only">Email adresa</label>
                <div class="relative flex-auto group">
                    <input
                        id="email-address"
                        type="email"
                        required
                        placeholder="Unesite vaš email"
                        class="w-full rounded-full bg-white/10 backdrop-blur-sm px-6 py-4 text-base text-white border border-white/20 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-300/60 focus:border-transparent transition-all duration-300 hover:bg-white/[0.15] hover:border-white/30"
                    />
                    <div class="absolute inset-0 rounded-full bg-gradient-to-r from-amber-400 to-rose-400 opacity-0 group-focus-within:opacity-20 transition-opacity duration-300 -z-10 blur-xl"></div>
                </div>

                <button
                    type="submit"
                    class="group relative overflow-hidden rounded-full bg-gradient-to-r from-[#8b6914] via-[#BBA14F] to-[#DBC584] px-8 py-4 text-xs sm:text-sm font-medium uppercase tracking-[0.28em] text-white shadow-[0_10px_30px_-8px_rgba(187,161,79,0.5)] transition-all duration-300 hover:shadow-[0_18px_40px_-10px_rgba(187,161,79,0.75)] hover:-translate-y-0.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
                >
                    <span class="relative z-10 inline-flex items-center gap-2">
                        Pošalji
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </span>
                    <span aria-hidden="true" class="pointer-events-none absolute inset-0 -translate-x-full bg-[linear-gradient(120deg,transparent_30%,rgba(255,255,255,0.4)_50%,transparent_70%)] transition-transform duration-700 ease-out group-hover:translate-x-full"></span>
                </button>
            </form>
        </div>
        </div>

        {{-- Floating particle sparkles --}}
        <div class="absolute top-20 left-20 w-1.5 h-1.5 bg-amber-300/60 rounded-full animate-bounce" style="animation-duration: 3s;"></div>
        <div class="absolute top-40 right-32 w-1 h-1 bg-amber-200/60 rounded-full animate-bounce" style="animation-duration: 2.5s; animation-delay: 0.5s;"></div>
        <div class="absolute bottom-32 left-1/3 w-1.5 h-1.5 bg-rose-300/50 rounded-full animate-bounce" style="animation-duration: 3.5s; animation-delay: 1s;"></div>
        <div class="absolute bottom-20 right-1/4 w-1 h-1 bg-amber-100/50 rounded-full animate-bounce" style="animation-duration: 2s; animation-delay: 0.3s;"></div>

      </div>
    </div>
</div>
