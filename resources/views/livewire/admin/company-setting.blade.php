<div class="flex h-full w-full flex-1 flex-col gap-6 bg-white dark:bg-neutral-900 rounded-xl p-6 shadow-lg transition">
    <x-page-heading headingText="Company Settings" descText="Manage your company settings" />

    <form wire:submit="updateCompanySetting" class="w-full space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:input wire:model="name" label="Name" type="text" required autofocus autocomplete="name" />
            <flux:input wire:model="phone" label="Phone" type="tel" required autocomplete="phone" />
            <flux:input wire:model="address" label="Address" type="text" required autocomplete="address" />
            <flux:input wire:model="value" label="Value" type="text" required autocomplete="value" />
        </div>

        <flux:textarea wire:model="description" label="Description" type="text" required autocomplete="description" class="mt-4" />

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <flux:button variant="danger" type="button" class="aspect-square hover:rotate-90 transition-transform" wire:click="resetFields">
                    <flux:icon.arrow-path class="size-4" />
                </flux:button>
                <flux:button variant="primary" type="submit" class="w-full sm:w-auto px-6 py-2 shadow-md hover:scale-[1.02] transition">
                    {{ __('Save') }}
                </flux:button>
            </div>

            <x-action-message class="text-sm text-green-500 dark:text-green-400" on="updated-company-setting">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</div>
