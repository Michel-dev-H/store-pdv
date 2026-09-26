<div>
    @if ($sent)
        <div class="rounded-2xl border border-brand/20 bg-brand-soft p-8 text-center">
            <svg class="mx-auto size-10 text-brand" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
            </svg>

            <p class="mt-4 font-semibold text-ink">{{ __('Mensagem recebida com carinho!') }}</p>
            <p class="mt-1 text-sm text-muted">{{ __('Retornaremos em breve.') }}</p>
        </div>
    @else
        <form wire:submit="submit" class="w-full space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <flux:field class="sm:col-span-2">
                    <flux:label>{{ __('Nome completo') }}</flux:label>
                    <flux:input wire:model="name" type="text" required autocomplete="name" placeholder="{{ __('Como gostaria de ser chamado?') }}" class="!rounded-xl focus:!ring-brand" />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('E-mail') }}</flux:label>
                    <flux:input wire:model="email" type="email" required autocomplete="email" placeholder="seu.melhor@email.com" class="!rounded-xl focus:!ring-brand" />
                    <flux:error name="email" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Telefone / WhatsApp') }}</flux:label>
                    <flux:input wire:model="phone" type="tel" autocomplete="tel" placeholder="(11) 99999-9999" class="!rounded-xl focus:!ring-brand" />
                    <flux:error name="phone" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label>{{ __('Mensagem') }}</flux:label>
                <flux:textarea wire:model="message" rows="4" required placeholder="{{ __('Conte para nós o que deseja encomendar ou sua dúvida...') }}" class="!rounded-xl focus:!ring-brand" />
                <flux:error name="message" />
            </flux:field>

            <div class="space-y-3">
                <flux:button variant="primary" type="submit" class="w-full !rounded-xl hover:!bg-brand-deep" wire:loading.attr="disabled">
                    <span>{{ __('Enviar mensagem') }}</span>

                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M13.5 4.5 21 12l-7.5 7.5" />
                        <path d="M21 12H3" />
                    </svg>
                </flux:button>

                <p class="flex items-center justify-center gap-1.5 text-center text-xs text-muted">
                    <svg class="size-3.5 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 12.75 11.25 15 15 9.75" />
                        <path d="M12 3.75a8.25 8.25 0 0 1 7.325 4.5 8.25 8.25 0 0 1-.8 8.325 8.25 8.25 0 0 1-6.525 3.675 8.25 8.25 0 0 1-6.525-3.675 8.25 8.25 0 0 1-.8-8.325A8.25 8.25 0 0 1 12 3.75Z" />
                    </svg>
                    {{ __('Seus dados estão protegidos. Respeitamos seu tempo e privacidade.') }}
                </p>
            </div>
        </form>
    @endif
</div>