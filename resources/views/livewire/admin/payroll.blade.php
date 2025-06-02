<div class="relative space-y-6">
    <x-page-heading headingText="Payrolls" descText="Manage your company payrolls" />

    {{-- Floating Button (FAB) --}}
    <div class="fixed bottom-6 right-6 z-50">
        <flux:button icon="plus" variant="primary" class="rounded-full shadow-lg px-6 py-4"
            wire:click="openModal">
            Create Payroll
        </flux:button>
    </div>

{{-- Payroll Table --}}
<div class="overflow-x-auto rounded-3xl shadow-xl border border-gray-200 dark:border-neutral-800 bg-gradient-to-tr from-white to-gray-50 dark:from-neutral-900 dark:to-neutral-950">
    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-neutral-700">
        <thead class="sticky top-0 z-10 bg-white/80 dark:bg-neutral-900/90 backdrop-blur text-gray-700 dark:text-neutral-300 uppercase text-xs font-bold border-b border-gray-200 dark:border-neutral-700">
            <tr>
                <th class="px-6 py-4 text-left tracking-wide">Period</th>
                <th class="px-6 py-4 text-left tracking-wide">Payment Date</th>
                <th class="px-6 py-4 text-left tracking-wide">Notes</th>
                <th class="px-6 py-4 text-left tracking-wide">Status</th>
                <th class="px-6 py-4 text-left tracking-wide">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-neutral-800 text-gray-800 dark:text-neutral-200">
            @forelse ($payrolls as $payroll)
                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition duration-150 ease-in-out group">
                    <td class="px-6 py-4 font-semibold whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($payroll->payroll_period_start)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($payroll->payroll_period_end)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($payroll->payment_date)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 max-w-xs truncate text-gray-600 dark:text-neutral-400">
                        {{ $payroll->notes }}
                    </td>
                    <td class="px-6 py-4">
                        @if ($payroll->payrollDetails->isEmpty())
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                                </svg>
                                Pending
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Generated
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            @if ($payroll->payrollDetails->isNotEmpty())
                                <flux:button icon="eye" variant="filled" class="transition transform hover:scale-105" wire:click="openViewModal({{ $payroll->id }})">
                                    View
                                </flux:button>
                            @endif

                            <flux:button icon="pencil" variant="primary" class="transition transform hover:scale-105" wire:click="openModal({{ $payroll->id }})">
                                Edit
                            </flux:button>

                            @if ($payroll->payrollDetails->isEmpty())
                                <flux:button icon="paper-airplane" variant="filled" class="transition transform hover:scale-105" wire:click="openGenerateModal({{ $payroll->id }})">
                                    Generate
                                </flux:button>

                                <flux:button icon="trash" variant="danger" class="transition transform hover:scale-105" wire:click="openDeleteModal({{ $payroll->id }})">
                                    Delete
                                </flux:button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-neutral-400">
                        No payrolls created yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

{{-- Pagination --}}
<div class="flex justify-end pt-4">
    {{ $payrolls->links() }}
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
                    @endif Payroll
                </flux:heading>
                <flux:text class="mt-2">
                    @if ($isEditting)
                        You're about to edit this payroll.
                    @else
                        Create a new payroll for your employee.
                    @endif
                </flux:text>
            </div>

            <flux:input wire:model="periodStart" label="Period Start" type="date" required />
            <flux:input wire:model="periodEnd" label="Period End" type="date" required />
            <flux:input wire:model="paymentDate" label="Payment Date" type="date" required />
            <flux:textarea wire:model="notes" label="Notes" placeholder="Notes" required />

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Modal Delete --}}
    <flux:modal name="delete-modal" class="min-w-[22rem]" wire:close="closeModal">
        <form wire:submit="delete" class="space-y-6">
            <div>
                <flux:heading size="lg">Delete
                    {{-- {{ $name }} --}}
                    ?
                </flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this payroll.</p>
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

    {{-- Generate Modal --}}
<flux:modal wire:close="closeModal" name="generate-modal">
    <div class="space-y-8">
        {{-- Header --}}
        <div class="text-center">
            <flux:heading size="lg" class="text-blue-600 dark:text-blue-400">
                <div class="inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-4h6v4m-6 0h6M7 17h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Generate Payroll
                </div>
            </flux:heading>
            <flux:text class="mt-3 text-gray-600 dark:text-neutral-400">
                This will generate the payroll for all employees in this period.<br class="hidden md:block">
                <strong class="text-red-500">This action cannot be reversed.</strong>
            </flux:text>
        </div>

        <flux:separator />

        {{-- Summary Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-neutral-900 p-4 rounded-xl shadow-inner">
            <div class="md:col-span-2">
                <flux:heading size="sm" class="text-gray-700 dark:text-neutral-300">Period</flux:heading>
                <flux:text class="mt-1 text-gray-600 dark:text-neutral-400">
                    {{ \Carbon\Carbon::parse($periodStart)->format('d M Y') }} –
                    {{ \Carbon\Carbon::parse($periodEnd)->format('d M Y') }}
                </flux:text>
            </div>
            <div>
                <flux:heading size="sm" class="text-gray-700 dark:text-neutral-300">Payment Date</flux:heading>
                <flux:text class="mt-1 text-gray-600 dark:text-neutral-400">
                    {{ \Carbon\Carbon::parse($paymentDate)->format('d M Y') }}
                </flux:text>
            </div>
            <div>
                <flux:heading size="sm" class="text-gray-700 dark:text-neutral-300">Notes</flux:heading>
                <flux:text class="mt-1 text-gray-600 dark:text-neutral-400">
                    {{ $notes }}
                </flux:text>
            </div>
        </div>

        <flux:separator />

        {{-- Form --}}
        <form class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <flux:checkbox.group wire:model="selectedAllowances" label="APPLY ALLOWANCES">
                    @foreach ($allowances as $allowance)
                        <flux:checkbox
                            value="{{ $allowance->id }}"
                            label="{{ $allowance->name }}"
                            description="{{ $allowance->description }}"
                        />
                    @endforeach
                </flux:checkbox.group>

                <flux:checkbox.group wire:model="selectedDeductions" label="APPLY DEDUCTIONS">
                    @foreach ($deductions as $deduction)
                        <flux:checkbox
                            value="{{ $deduction->id }}"
                            label="{{ $deduction->name }}"
                            description="{{ $deduction->description }}"
                        />
                    @endforeach
                </flux:checkbox.group>
            </div>

            <flux:separator />

            {{-- Action Button --}}
            <div class="flex justify-end">
                <flux:button type="submit" variant="primary" class="transition-transform hover:scale-105">
                    <div class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Generate
                    </div>
                </flux:button>
            </div>
        </form>
    </div>
</flux:modal>

</div>
