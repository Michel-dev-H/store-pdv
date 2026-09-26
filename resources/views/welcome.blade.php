@php
    $fluxAppearanceLocked = true;

    $navLink = 'text-sm font-medium text-muted transition hover:text-ink';

    $destaques = [
        ['mug', __('Cafés especiais'), __('Blends selecionados e métodos que valorizam o grão.')],
        ['bread', __('Pães e bolos artesanais'), __('Pães de fermentação natural e bolos da casa, feitos todas as manhãs.')],
        ['basket', __('Cestas e combos'), __('Cestas prontas para levar o melhor da padaria para sua mesa.')],
    ];

    $contato = [
        ['map-pin', __('Endereço'), 'Rua das Laranjeiras, 142 — Centro'],
        ['envelope', __('E-mail'), 'ola@graoecia.com.br'],
        ['phone', __('Telefone e WhatsApp'), '(11) 99999-9999'],
    ];

    $contatoIcons = [
        'map-pin' => '<path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /><path d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
        'envelope' => '<path d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />',
        'phone' => '<path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />',
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body
        class="min-h-screen bg-canvas font-sans text-ink antialiased selection:bg-brand selection:text-white"
        style="--color-accent: var(--color-brand)"
    >
        <header class="sticky top-0 z-40 border-b border-line/30 bg-canvas/80 backdrop-blur-md">
            <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <a href="#inicio" class="group flex shrink-0 items-center gap-2">
                    <span class="flex aspect-square size-8 items-center justify-center rounded-full border border-line bg-brand-soft/80 text-brand transition group-hover:bg-brand-soft">
                        <x-app-logo-icon class="size-5 fill-current" />
                    </span>
                    <span class="font-semibold tracking-tight text-ink">{{ __('Grão & Cia') }}</span>
                </a>

                <div class="flex items-center gap-1">
                    <a href="#produtos" class="{{ $navLink }} rounded-lg px-3 py-2">{{ __('Produtos') }}</a>
                    <a href="#contato" class="{{ $navLink }} rounded-lg px-3 py-2">{{ __('Contato') }}</a>

                    @auth
                        <flux:button variant="primary" size="sm" :href="route('pdv')" class="ms-2 !rounded-full hover:!bg-brand-deep">
                            {{ __('Dashboard') }}
                        </flux:button>
                    @else
                        <flux:button variant="primary" size="sm" :href="route('login')" class="ms-2 !rounded-full hover:!bg-brand-deep">
                            {{ __('Login') }}
                        </flux:button>
                    @endauth
                </div>
            </nav>
        </header>

        <main>
            <section id="inicio" class="relative flex min-h-[100dvh] scroll-mt-20 items-center overflow-hidden">
                <div aria-hidden="true" class="absolute inset-0">
                    <div class="absolute -top-24 right-[-6rem] size-96 rounded-full bg-ember/50 blur-3xl"></div>
                    <div class="absolute bottom-0 left-[15%] size-80 rounded-full bg-brand-soft/60 blur-3xl"></div>
                    <div class="absolute bottom-1/4 right-[10%] size-72 rounded-full bg-ember/40 blur-3xl"></div>
                    <div class="absolute inset-0 opacity-[0.05]" style="background-image: radial-gradient(#6e5f4e 0.65px, transparent 0.65px); background-size: 24px 24px;"></div>
                </div>

                <div aria-hidden="true" class="pointer-events-none absolute -right-36 top-1/2 hidden -translate-y-1/2 lg:block">
                    <div class="relative size-[26rem] rounded-t-full rounded-b-3xl border border-line bg-gradient-to-b from-surface via-[#faf4ea] to-canvas p-5 shadow-[0_24px_60px_-30px_rgba(110,95,78,0.35)]">
                        <div class="relative flex size-full flex-col items-center justify-between rounded-t-full rounded-b-2xl border border-line/70 bg-gradient-to-b from-canvas/40 to-transparent p-8">
                            <div class="flex flex-col items-center">
                                <div class="flex size-12 items-center justify-center rounded-full border border-line bg-surface/80 text-brand shadow-sm">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4.5 12.5a7.5 7.5 0 0 1 15 0v1.5a1.5 1.5 0 0 1-1.5 1.5H6a1.5 1.5 0 0 1-1.5-1.5v-1.5Z" />
                                        <path d="M9.5 9.5c-.5-1.5.5-2.5 2-3" />
                                        <path d="M13.5 8.5c0-1.6 1.4-2.6 3-3" />
                                    </svg>
                                </div>
                                <div class="mt-2.5 h-8 w-px bg-gradient-to-b from-line to-transparent"></div>
                            </div>

                            <div class="space-y-2.5 text-center">
                                <div class="mx-auto h-px w-6 bg-brand/40"></div>
                                <p class="font-semibold tracking-tight text-ink">{{ __('Artesania Pura') }}</p>
                                <p class="text-[11px] font-medium uppercase tracking-widest text-muted">
                                    {{ __('Farinhas Selecionadas & Leveduras Selvagens') }}
                                </p>
                                <div class="mx-auto h-px w-6 bg-brand/40"></div>
                            </div>

                            <div class="flex w-full items-center justify-between border-t border-line/60 pt-5 text-[11px] text-muted">
                                <span>EST. 2021</span>
                                <span class="inline-flex items-center gap-1 font-medium text-brand">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 3.75a6.715 6.715 0 0 0-3.722 1.118c-2.949 1.924-3.951 5.693-2.254 8.631.198.345.336.713.43 1.077a4.77 4.77 0 0 1 5.304-1.817 5.53 5.53 0 0 0-.47 2.97.75.75 0 0 0 1.497.081 6.03 6.03 0 0 1 .483-1.347 4.5 4.5 0 0 1 .574-6.102c.99-.9 2.16-1.277 3.253-1.516.76-.167 1.663-.28 2.603-.376A.75.75 0 0 0 20 3.007c-.165-1.08-1.6-2.018-3.28-1.257" />
                                        <path d="M16 5.25h-.008v.008H16v-.008Z" />
                                    </svg>
                                    {{ __('100% Orgânico') }}
                                </span>
                                <span>LOTE Nº 48</span>
                            </div>
                        </div>

                        <div class="absolute -bottom-5 -left-10 hidden items-center gap-3 rounded-2xl border border-line bg-surface p-4 shadow-lg sm:flex">
                            <span class="flex size-10 items-center justify-center rounded-xl bg-brand-soft/60 text-brand">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 11h12v5a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3v-5Z" />
                                    <path d="M16 12h1.5a2.5 2.5 0 0 1 0 5H16" />
                                    <path d="M8.5 7.5c0-.8.7-1.4 1.5-1.4s1.5.6 1.5 1.4" />
                                    <path d="M12.5 7.5c0-.8.7-1.4 1.5-1.4s1.5.6 1.5 1.4" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs font-semibold text-ink">{{ __('Cafés Especiais') }}</p>
                                <p class="text-[11px] text-muted">{{ __('Torra fresca da semana') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative mx-auto grid w-full max-w-6xl grid-cols-1 gap-16 px-4 py-24 sm:px-6 lg:grid-cols-12 lg:gap-20 lg:px-8">
                    <div class="lg:col-span-7">
                        <p class="inline-flex items-center gap-2.5 rounded-full border border-line bg-surface px-3.5 py-1.5 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                            <span class="relative flex size-2">
                                <span class="absolute inline-flex size-full animate-pulse rounded-full bg-brand opacity-75"></span>
                                <span class="relative inline-flex size-2 rounded-full bg-brand"></span>
                            </span>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-muted">{{ __('Padaria artesanal') }}</span>
                        </p>

                        <flux:heading
                            level="1"
                            size="2xl"
                            class="!mt-6 max-w-2xl text-balance !text-4xl !font-semibold !tracking-tight !text-ink sm:!text-5xl lg:!text-6xl"
                        >
                            {{ __('Sabor que abraça') }} <span class="italic">{{ __('o seu dia') }}</span>
                        </flux:heading>

                        <flux:subheading size="xl" class="!mt-6 !max-w-xl !text-lg !leading-relaxed !text-muted">
                            {{ __('Cafés selecionados, pães e bolos artesanais preparados todos os dias. Visite a Grão & Cia e experimente o melhor da nossa casa.') }}
                        </flux:subheading>

                        <div class="mt-8 flex flex-wrap items-center gap-4">
                            @auth
                                <flux:button variant="primary" :href="route('pdv')" class="!h-12 !rounded-full !px-7 hover:!bg-brand-deep">
                                    {{ __('Ir para o PDV') }}
                                </flux:button>
                            @else
                                <flux:button variant="primary" :href="route('login')" class="!h-12 !rounded-full !px-7 hover:!bg-brand-deep">
                                    {{ __('Entrar') }}
                                </flux:button>
                            @endauth

                            <flux:button
                                variant="outline"
                                href="#produtos"
                                class="!rounded-full !border-line !text-ink hover:!bg-brand-soft/50 hover:!border-brand/40"
                            >
                                {{ __('Conheça nossos produtos') }}
                            </flux:button>
                        </div>

                        <div class="mt-14 flex w-full flex-wrap items-center gap-x-10 gap-y-4 border-t border-line/80 pt-8">
                            <span class="inline-flex items-center gap-2 text-sm text-muted">
                                <svg class="size-4 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="8.25" />
                                    <path d="M12 7.5V12l2.75 1.65" />
                                </svg>
                                {{ __('Fornadas quentes às 07h e 16h') }}
                            </span>
                            <span class="inline-flex items-center gap-2 text-sm text-muted">
                                <svg class="size-4 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 4v9.5" />
                                    <path d="M9 7.5h1.5" />
                                    <circle cx="12" cy="17.5" r="2.5" />
                                </svg>
                                {{ __('Fermentação lenta de 36 horas') }}
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <section id="produtos" class="flex min-h-[100dvh] scroll-mt-20 items-center border-t border-line/70">
                <div class="mx-auto grid w-full max-w-7xl grid-cols-1 gap-16 px-4 py-24 sm:px-6 lg:grid-cols-12 lg:gap-16 xl:gap-24 lg:px-8">
                    <div class="flex flex-col justify-between lg:col-span-5 lg:sticky lg:top-28">
                        <div>
                            <p class="inline-flex w-fit items-center gap-2 rounded-full border border-brand/10 bg-brand-soft/60 px-3.5 py-1.5 text-brand">
                                <span class="relative flex size-1.5">
                                    <span class="absolute inline-flex size-full animate-pulse rounded-full bg-brand opacity-75"></span>
                                    <span class="relative inline-flex size-1.5 rounded-full bg-brand"></span>
                                </span>
                                <span class="text-xs font-semibold uppercase tracking-wider">{{ __('Nossos destaques') }}</span>
                            </p>

                            <flux:heading
                                level="2"
                                size="2xl"
                                class="mt-6 max-w-xl text-balance !text-4xl !font-semibold !tracking-tight !text-ink sm:!text-5xl lg:!text-7xl"
                            >
                                {{ __('Pães quentinhos,') }} <span class="italic">{{ __('todos os dias.') }}</span>
                            </flux:heading>

                            <flux:subheading size="lg" class="!mt-6 !max-w-xl !leading-relaxed !text-muted">
                                {{ __('Receitas desenvolvidas com paciência, ingredientes limpos e o respeito ao tempo natural da fermentação. Cada detalhe é pensado para transformar o cotidiano em aconchego.') }}
                            </flux:subheading>

                            <div class="mt-8 flex flex-wrap items-center gap-5">
                                <flux:button variant="primary" href="#contato" class="!h-11 !rounded-full !px-6 hover:!bg-brand-deep">
                                    <span>{{ __('Ver cardápio completo') }}</span>

                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M13.5 4.5 21 12l-7.5 7.5" />
                                        <path d="M21 12H3" />
                                    </svg>
                                </flux:button>

                                <span class="inline-flex items-center gap-2 rounded-full border border-line/60 bg-surface/60 px-4 py-2 text-xs text-muted">
                                    <svg class="size-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 3.75a6.715 6.715 0 0 0-3.722 1.118c-2.949 1.924-3.951 5.693-2.254 8.631.198.345.336.713.43 1.077a4.77 4.77 0 0 1 5.304-1.817 5.53 5.53 0 0 0-.47 2.97.75.75 0 0 0 1.497.081 6.03 6.03 0 0 1 .483-1.347 4.5 4.5 0 0 1 .574-6.102c.99-.9 2.16-1.277 3.253-1.516.76-.167 1.663-.28 2.603-.376A.75.75 0 0 0 20 3.007c-.165-1.08-1.6-2.018-3.28-1.257" />
                                        <path d="M16 5.25h-.008v.008H16v-.008Z" />
                                    </svg>
                                    {{ __('Ingredientes 100% naturais') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-7">
                        <div class="divide-y divide-line/40 border-y border-line/40">
                            @foreach ($destaques as [$icon, $title, $text])
                                <article class="group -mx-3 rounded-xl px-3 py-8 transition-all duration-300 hover:bg-surface/40 md:-mx-6 md:px-6 md:py-10">
                                    <div class="flex items-start justify-between gap-4 md:items-center md:gap-8">
                                        <div class="flex items-start gap-6 md:items-center md:gap-8">
                                            <span class="w-10 shrink-0 select-none text-3xl tabular-nums text-muted/40 transition-colors duration-300 group-hover:text-brand md:text-4xl">
                                                {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                            </span>

                                            <span class="flex size-12 shrink-0 items-center justify-center rounded-full border border-brand/20 bg-brand-soft/50 text-brand transition-all duration-300 group-hover:scale-105 group-hover:bg-brand group-hover:text-white">
                                                @if ($icon === 'mug')
                                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path d="M4 11h12v5a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3v-5Z" />
                                                        <path d="M16 12h1.5a2.5 2.5 0 0 1 0 5H16" />
                                                        <path d="M8.5 7.5c0-.8.7-1.4 1.5-1.4s1.5.6 1.5 1.4" />
                                                        <path d="M12.5 7.5c0-.8.7-1.4 1.5-1.4s1.5.6 1.5 1.4" />
                                                    </svg>
                                                @elseif ($icon === 'bread')
                                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path d="M4.5 12.5a7.5 7.5 0 0 1 15 0v1.5a1.5 1.5 0 0 1-1.5 1.5H6a1.5 1.5 0 0 1-1.5-1.5v-1.5Z" />
                                                        <path d="M9.5 9.5c-.5-1.5.5-2.5 2-3" />
                                                        <path d="M13.5 8.5c0-1.6 1.4-2.6 3-3" />
                                                    </svg>
                                                @else
                                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5" />
                                                        <path d="M11.25 10.5H6.633a1.5 1.5 0 0 0-1.483 1.19l-1.8 8.1A3 3 0 0 0 6.25 23h11.5a3 3 0 0 0 2.9-3.21l-1.8-8.1a1.5 1.5 0 0 0-1.483-1.19h-4.617" />
                                                    </svg>
                                                @endif
                                            </span>

                                            <div class="space-y-1">
                                                <h3 class="text-xl font-semibold tracking-tight text-ink transition-colors duration-200 group-hover:text-brand md:text-2xl">{{ $title }}</h3>
                                                <p class="text-sm leading-relaxed text-muted md:text-base">{{ $text }}</p>
                                            </div>
                                        </div>

                                        <span class="flex size-10 shrink-0 items-center justify-center rounded-full border border-transparent text-muted/40 transition-all duration-200 group-hover:translate-x-1.5 group-hover:border-line/80 group-hover:text-brand" aria-hidden="true">
                                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M13.5 4.5 21 12l-7.5 7.5" />
                                                <path d="M21 12H3" />
                                            </svg>
                                        </span>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-8 flex items-center justify-between text-xs text-muted/70">
                            <span class="flex items-center gap-2">
                                <svg class="size-3.5 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="8.25" />
                                    <path d="M12 7.5V12l2.75 1.65" />
                                </svg>
                                {{ __('Fornos acesos a partir das 06:00') }}
                            </span>
                            <span class="tracking-wide">{{ __('LOTE LIMITADO DIÁRIO') }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <section id="contato" class="flex min-h-[100dvh] scroll-mt-20 items-center border-t border-line/70 bg-canvas">
                <div class="mx-auto grid w-full max-w-7xl grid-cols-1 gap-16 px-4 py-24 sm:px-6 lg:grid-cols-12 lg:gap-16 lg:px-8">
                    <div class="flex flex-col justify-between space-y-10 lg:col-span-5">
                        <div class="space-y-4">
                            <p class="inline-flex w-fit items-center gap-2 rounded-full border border-brand/15 bg-brand-soft px-3.5 py-1.5 text-brand">
                                <span class="relative flex size-1.5">
                                    <span class="absolute inline-flex size-full animate-pulse rounded-full bg-brand opacity-75"></span>
                                    <span class="relative inline-flex size-1.5 rounded-full bg-brand"></span>
                                </span>
                                <span class="text-xs font-semibold uppercase tracking-wider">{{ __('Contato') }}</span>
                            </p>

                            <flux:heading
                                level="2"
                                size="2xl"
                                class="text-balance !text-4xl !font-semibold !tracking-tight !text-ink sm:!text-5xl lg:!text-[3.25rem]"
                            >
                                {{ __('Estamos esperando por você.') }}
                            </flux:heading>

                            <flux:subheading size="lg" class="!max-w-[56ch] !leading-relaxed !text-muted">
                                {{ __('Venha tomar um café passado na hora, sentir o cheirinho de pão quente ou mande uma mensagem para encomendar fornadas especiais.') }}
                            </flux:subheading>
                        </div>

                        <div class="space-y-3.5">
                            @foreach ($contato as [$icon, $label, $value])
                                <div class="flex items-start gap-4 rounded-xl border border-line bg-surface/80 p-4 shadow-sm transition hover:border-brand/30">
                                    <span class="flex size-10 shrink-0 items-center justify-center rounded-lg border border-line bg-canvas text-brand">
                                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            {!! $contatoIcons[$icon] !!}
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-medium uppercase tracking-wider text-muted">{{ $label }}</p>

                                        @if ($icon === 'envelope')
                                            <a href="mailto:{{ $value }}" class="mt-0.5 inline-block font-medium text-ink transition hover:text-brand">{{ $value }}</a>
                                        @elseif ($icon === 'phone')
                                            <a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $value) }}" class="mt-0.5 inline-block font-medium text-ink transition hover:text-brand">{{ $value }}</a>
                                        @else
                                            <p class="mt-0.5 font-medium text-ink">{{ $value }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <div class="flex items-center gap-3.5 rounded-xl border border-line bg-brand-soft/50 p-4">
                                <svg class="size-5 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="8.25" />
                                    <path d="M12 7.5V12l2.75 1.65" />
                                </svg>
                                <span class="text-sm font-medium text-ink">
                                    <span class="text-muted">{{ __('Horário: ') }}</span>{{ __('Terça a Domingo, das 07h às 19h') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-7">
                        <div class="rounded-3xl border border-line bg-surface/95 p-8 shadow-[0_24px_60px_-24px_rgba(33,26,18,0.22)] sm:p-10 md:p-12">
                            <header class="mb-8 border-b border-line/60 pb-6">
                                <flux:heading level="3" size="lg" class="!text-2xl !font-semibold !tracking-tight !text-ink">
                                    {{ __('Envie sua mensagem') }}
                                </flux:heading>

                                <flux:text class="!mt-1.5 !text-sm !text-muted">
                                    {{ __('Retornamos seu contato em até poucas horas.') }}
                                </flux:text>
                            </header>

                            <livewire:contact-form />
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-line/40 bg-container">
            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 px-4 py-12 sm:flex-row sm:px-6 lg:px-8">
                <div class="flex flex-col items-center gap-1 text-center sm:items-start sm:text-left">
                    <flux:text variant="subtle" size="sm">
                        © {{ date('Y') }} {{ __('Grão & Cia. Todos os direitos reservados.') }}
                    </flux:text>
                    <flux:text variant="subtle" size="xs">
                        {{ __('Feito com afeto artesanal.') }}
                    </flux:text>
                </div>

                <nav class="flex flex-wrap items-center justify-center gap-6">
                    <a href="#inicio" class="{{ $navLink }}">{{ __('Início') }}</a>
                    <a href="#produtos" class="{{ $navLink }}">{{ __('Produtos') }}</a>
                    <a href="#contato" class="{{ $navLink }}">{{ __('Contato') }}</a>
                </nav>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>