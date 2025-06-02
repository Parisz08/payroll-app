<div class="flex flex-col gap-6 rounded-xl bg-white dark:bg-neutral-900 p-6 shadow-lg transition">
    <x-page-heading headingText="Tax Settings" descText="Manage your company PPh tax settings" />

    <div class="flex justify-end">
        <flux:modal.trigger name="main-modal">
            <flux:button icon="plus" variant="primary" type="button" class="w-fit">
                {{ __('Add Tax') }}
            </flux:button>
        </flux:modal.trigger>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-neutral-700 shadow-sm">
        <table class="w-full table-auto text-sm text-left text-gray-700 dark:text-neutral-300">
            <thead class="bg-gray-50 dark:bg-neutral-800 text-xs uppercase tracking-wider text-gray-600 dark:text-neutral-400 border-b dark:border-neutral-700">
                <tr>
                    <th class="p-4 w-12 font-semibold">{{ __('No') }}</th>
                    <th class="p-4 font-semibold">{{ __('Name') }}</th>
                    <th class="p-4 font-semibold">{{ __('Rate') }}</th>
                    <th class="p-4 font-semibold">{{ __('Threshold') }}</th>
                    <th class="p-4 font-semibold">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                @foreach ($taxes as $tax)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/30 transition">
                        <td class="px-4 py-3">
                            {{ $loop->iteration + ($taxes->currentPage() - 1) * $taxes->perPage() }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-neutral-100">
                            {{ $tax->name }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $tax->rate * 100 }}%
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $threshold = explode('-', $tax->threshold);
                                $lowerBound = $threshold[0];
                                $upperBound = $threshold[1];
                            @endphp
                            <span class="text-gray-600 dark:text-neutral-300">
                                Rp {{ number_format($lowerBound, 0, ',', '.') }}
                                <span class="mx-1 text-gray-400 dark:text-neutral-500">→</span>
                                Rp {{ number_format($upperBound, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <flux:button wire:click="openModal('edit', {{ $tax->id }})" icon="pencil-square" variant="primary" type="button">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button wire:click="openDeleteModal('{{ $tax->id }}', '{{ $tax->name }}')" icon="trash" variant="danger" type="button">
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-end">
        {{ $taxes->links() }}
    </div>
    

    {{-- Main Modal --}}
    <flux:modal wire:close="closeModal" name="main-modal" class="md:w-96">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">
                    @if ($isEditting)
                        Edit
                    @else
                        New
                    @endif Tax
                </flux:heading>
                <flux:text class="mt-2">
                    @if ($isEditting)
                        Update tax to the system. This will allow you to manage your employee PPh tax more effectively.
                    @else
                        Add a new tax to the system. This will allow you to manage your employee PPh tax more
                        effectively.
                    @endif
                </flux:text>
            </div>
            <flux:input wire:model="name" label="Name" placeholder="Name" required />
            <flux:textarea wire:model="description" label="Description" placeholder="Description" />
            <flux:input wire:model="rate" label="Rate" placeholder="Rate" type="number" min="0" step="0.01"
                max="1" required />
            <flux:text class="mt-2">
                Rate value must be in percentage format, <br />e.g. 5% = 0.05
            </flux:text>

            <flux:input wire:model="lowerBound" label="Lower Bound" placeholder="Lower Bound" required />
            <flux:input wire:model="upperBound" label="Upper Bound" placeholder="Upper Bound" required />

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Modal Delete --}}
    <flux:modal name="delete-modal" class="min-w-[22rem]" wire:close="closeModal">
        <form 
            wire:submit="deleteTax"
        class="space-y-6">
            <div>
                <flux:heading size="lg">Delete
                    {{ $name }}
                    ?
                </flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this tax.</p>
                    <p>This action cannot be reversed.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger">
                    Delete</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
