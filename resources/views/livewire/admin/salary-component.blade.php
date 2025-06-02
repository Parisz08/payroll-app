<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-neutral-900 p-6 shadow-lg transition">
    <x-page-heading headingText="Salary Component" descText="Manage your company salary components" />

    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Allowances</h2>
        <flux:button wire:click="openModal('allowance')" icon="plus" variant="primary" type="button" class="w-fit">
            {{ __('Add Allowance') }}
        </flux:button>
    </div>

    {{-- Allowance Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-neutral-700 shadow-sm">
        <table class="w-full table-auto text-sm text-left text-gray-700 dark:text-neutral-300">
            <thead class="bg-gray-50 dark:bg-neutral-800 text-xs uppercase tracking-wider text-gray-600 dark:text-neutral-400 border-b dark:border-neutral-700">
                <tr>
                    <th class="p-4 w-12 font-semibold">{{ __('No') }}</th>
                    <th class="p-4 font-semibold">{{ __('Name') }}</th>
                    <th class="p-4 font-semibold">{{ __('Amount') }}</th>
                    <th class="p-4 font-semibold">{{ __('Rule') }}</th>
                    <th class="p-4 font-semibold">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                @forelse ($allowances as $allowance)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/30 transition">
                        <td class="px-4 py-3">{{ $loop->iteration + ($allowances->currentPage() - 1) * $allowances->perPage() }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-neutral-100">{{ $allowance->name }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-neutral-300">
                            @if ($allowance->rule == 'fixed')
                                Rp {{ number_format($allowance->amount, 0, ',', '.') }}
                            @else
                                {{ number_format($allowance->amount * 100, 0, ',', '.') }}%
                            @endif
                        </td>
                        <td class="px-4 py-3 capitalize text-gray-500 dark:text-neutral-400">{{ $allowance->rule }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2 flex-wrap">
                                <flux:button wire:click="openModal('allowance', {{ $allowance->id }})" icon="pencil-square" variant="primary" type="button">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button wire:click="openDeleteModal('allowance', '{{ $allowance->id }}', '{{ $allowance->name }}')" icon="trash" variant="danger" type="button">
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-neutral-400 italic">
                            No allowances found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end">
        {{ $allowances->links() }}
    </div>


    {{-- Main Modal Add and Edit --}}
    <flux:modal wire:close="closeModal" name="main-modal" class="md:w-96">
        <form 
            @if ($modalType == 'allowance')
                @if ($isEditting)
                    wire:submit="updateAllowance"
                @else
                    wire:submit="addAllowance"
                @endif
            @else
                @if ($isEditting)
                    wire:submit="updateDeduction"
                @else
                    wire:submit="addDeduction"
                @endif
            @endif
            class="space-y-6">
            <div>
                @if ($modalType == 'allowance')
                    <flux:heading size="lg">@if ($isEditting) Edit @else New @endif Allowance</flux:heading>
                    <flux:text class="mt-2">
                        @if ($isEditting)
                        Update allowance to the system. This will allow you to manage your allowances more effectively.
                        @else
                        Add a new allowance to the system. This will allow you to manage your allowances more effectively.
                        @endif
                    </flux:text>
                @else
                    <flux:heading size="lg">@if ($isEditting) Edit @else New @endif Deduction</flux:heading>
                    <flux:text class="mt-2">
                        @if ($isEditting)
                        Update deduction to the system. This will allow you to manage your deductions more effectively.
                        @else
                        Add a new deduction to the system. This will allow you to manage your deductions more effectively.
                        @endif
                    </flux:text>
                @endif
            </div>
            <flux:input wire:model="name" label="Name" placeholder="Name" required />
            <flux:textarea wire:model="description" label="Description" placeholder="Description" />
            <flux:input wire:model="amount" label="Amount" placeholder="Amount" required />
            @if ($modalType == 'allowance')
                <flux:text class="mt-2">
                    For Rule "Percentage".<br /> 1 is equal to 100%,<br /> 0.5 is equal to 50%.
                </flux:text>
                <flux:select label="Rule" wire:model="rule" placeholder="Choose rule..." required>
                    <flux:select.option value="fixed">Fixed</flux:select.option>
                    <flux:select.option value="percentage">Percentage</flux:select.option>
                </flux:select>
            @endif
            
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Modal Delete --}}
    <flux:modal name="delete-modal" class="min-w-[22rem]" wire:close="closeModal">
        <form 
        @if ($modalType == 'allowance')
            wire:submit="deleteAllowance"
        @else
            wire:submit="deleteDeduction"
        @endif
        class="space-y-6">
            <div>
                <flux:heading size="lg">Delete
                    {{ $name }}
                    ?
                </flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this @if ($modalType == 'allowance') allowance @else deduction @endif.</p>
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
    
    <flux:separator />

    <h2 class="text-xl font-semibold">Deductions</h2>

    {{-- Add Deductions --}}
    <flux:button wire:click="openModal('deduction')" icon="plus" variant="primary" type="button" class="w-fit">
        {{ __('Add Deductions') }}
    </flux:button>

    {{-- Deductions Table --}}
    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="text-left text-sm uppercase font-bold border-b">
                <th scope="col" class="p-4 w-12">
                    {{ __('No') }}
                </th>
                <th scope="col" class="p-4">
                    {{ __('Name') }}
                </th>
                <th scope="col" class="p-4">
                    {{ __('Amount') }}
                </th>
                <th scope="col" class="p-4">
                    {{ __('Actions') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($deductions as $deduction)
                <tr class="border-b hover:bg-gray-50/5">
                    <td class="px-4 py-2">
                        {{ $loop->iteration + ($deductions->currentPage() - 1) * $deductions->perPage() }}
                    </td>
                    <td class="px-4 py-2">
                        {{ $deduction->name }}
                    </td>
                    <td class="px-4 py-2">
                            Rp {{ number_format($deduction->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            <flux:button wire:click="openModal('deduction', {{ $deduction->id }})" icon="pencil-square" variant="primary" type="button">
                                {{ __('Edit') }}
                            </flux:button>
                            <flux:button wire:click="openDeleteModal('deduction', '{{ $deduction->id }}', '{{ $deduction->name }}')" icon="trash" variant="danger" type="button">
                                {{ __('Delete') }}
                            </flux:button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{$deductions->links()}}

</div>
