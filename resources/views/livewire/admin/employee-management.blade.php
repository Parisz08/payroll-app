<di class="space-y-6">
    <x-page-heading headingText="Employee Management" descText="Manage your employees" />

    <div class="flex justify-end">
        <flux:modal.trigger name="main-modal">
            <flux:button icon="plus" variant="primary" type="button" class="w-fit">
                {{ __('Register an Employee') }}
            </flux:button>
        </flux:modal.trigger>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm">
        <table class="min-w-full table-auto text-sm text-left text-gray-700 dark:text-neutral-300">
            <thead class="bg-gray-50 dark:bg-neutral-800 border-b border-gray-200 dark:border-neutral-700 text-xs uppercase tracking-wide text-gray-600 dark:text-neutral-400">
                <tr>
                    <th class="px-6 py-4 w-12 font-semibold">{{ __('No') }}</th>
                    <th class="px-6 py-4 font-semibold">{{ __('Full Name') }}</th>
                    <th class="px-6 py-4 font-semibold">{{ __('Hire Date') }}</th>
                    <th class="px-6 py-4 font-semibold">{{ __('Department & Position') }}</th>
                    <th class="px-6 py-4 font-semibold">{{ __('Base Salary') }}</th>
                    <th class="px-6 py-4 font-semibold">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                @foreach ($employees as $employee)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/30 transition">
                        <td class="px-6 py-4">
                            {{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-neutral-100">
                            {{ $employee->full_name }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-neutral-400">
                            {{ \Carbon\Carbon::parse($employee->hire_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800 dark:text-neutral-200">{{ $employee->position->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-neutral-400">{{ $employee->position->department->name }} Dept.</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800 dark:text-neutral-100">
                            Rp {{ number_format($employee->salary->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2 flex-wrap">
                                <flux:button wire:click="openModal('view', {{ $employee->id }})" icon="eye" variant="filled" type="button">
                                    {{ __('View') }}
                                </flux:button>
                                <flux:button wire:click="openModal('edit', {{ $employee->id }})" icon="pencil-square" variant="primary" type="button">
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button wire:click="openDeleteModal('{{ $employee->id }}', '{{ $employee->full_name }}')" icon="trash" variant="danger" type="button">
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if ($employees->isEmpty())
                    <tr>
                        <td colspan="6" class="px-6 py-5 text-center italic text-sm text-gray-500 dark:text-neutral-400">
                            {{ __('No employees found.') }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="flex justify-end">
        {{ $employees->links() }}
    </div>


    {{-- Main Modal --}}
    <flux:modal wire:close="closeModal" name="main-modal" class="w-full md:min-w-3/4">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">
                    @if ($isEditting)
                        Edit
                    @else
                        New
                    @endif Employee
                </flux:heading>
                <flux:text class="mt-2">
                    @if ($isEditting)
                        Update employee to the system. This will allow you to manage your employees more effectively.
                    @else
                        Add a new employee to the system. This will allow you to manage your employees more effectively.
                    @endif
                </flux:text>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="email" label="Email" placeholder="Email" required />
                @if ($isEditting)
                    <flux:input wire:model="password" label="Password" placeholder="Password" type="password" required disabled />
                @else
                    <flux:input wire:model="password" label="Password" placeholder="Password" type="password" required />
                @endif
            </div>

            <flux:separator />

            <flux:input wire:model="fullName" label="Full Name" placeholder="Full Name" required />
            <flux:textarea wire:model="address" label="Address" placeholder="Address" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="phone" label="Phone" placeholder="Phone" type="tel" required />
                <flux:input wire:model="hireDate" label="Hire Date" placeholder="Hire Date" type="date" required />
                <flux:select label="Department" wire:model="selectedDepartmentId" wire:change="updatePositions" placeholder="Choose department..."
                    required>
                    @foreach ($departments as $department)
                        <flux:select.option value="{{ $department->id }}">{{ $department->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select label="Position" wire:model="selectedPositionId" placeholder="Choose position..." required>
                    @foreach ($positions as $position)
                        <flux:select.option value="{{ $position->id }}">{{ $position->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <flux:separator />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="bankName" label="Bank Name" placeholder="Bank Name" required />
                <flux:input wire:model="bankAccount" label="Bank Account" placeholder="Bank Account" required />
                <flux:input wire:model="npwp" label="NPWP" placeholder="NPWP" />
                <flux:input wire:model="salary" label="Base Salary" placeholder="Base Salary" type="number" min="0" required />
                <flux:select wire:model="payFrequency" label="Pay Frequency" placeholder="Pay Frequency" required>
                    <flux:select.option value="weekly">Weekly</flux:select.option>
                    <flux:select.option value="monthly">Monthly</flux:select.option>
                </flux:select>
                <flux:input wire:model="effectiveDate" label="Effective Date" placeholder="Effective Date" type="date" required />
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Modal Delete --}}
    <flux:modal name="delete-modal" class="min-w-[22rem]" wire:close="closeModal">
        <form 
            wire:submit="delete"
        class="space-y-6">
            <div>
                <flux:heading size="lg">Delete
                    {{ $fullName }}
                    ?
                </flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this employee.</p>
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

    {{-- View Modal --}}
{{-- Modal Add/Edit Employee --}}
<flux:modal wire:close="closeModal" name="main-modal" class="w-full md:min-w-[80%] p-6 rounded-xl shadow-lg bg-white dark:bg-neutral-900">
    <form wire:submit="save" class="space-y-8">
        <div class="space-y-1">
            <flux:heading size="lg" class="text-2xl font-semibold text-gray-800 dark:text-neutral-100">
                {{ $isEditting ? 'Edit' : 'New' }} Employee
            </flux:heading>
            <flux:text class="text-sm text-gray-600 dark:text-neutral-400 leading-relaxed">
                {{ $isEditting 
                    ? 'Update employee to the system. This will allow you to manage your employees more effectively.' 
                    : 'Add a new employee to the system. This will allow you to manage your employees more effectively.' }}
            </flux:text>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:input wire:model="email" label="Email" placeholder="Email" required />
            @if ($isEditting)
                <flux:input wire:model="password" label="Password" placeholder="Password" type="password" required disabled />
            @else
                <flux:input wire:model="password" label="Password" placeholder="Password" type="password" required />
            @endif
        </div>

        <flux:separator class="my-2" />

        <div class="space-y-4">
            <flux:input wire:model="fullName" label="Full Name" placeholder="Full Name" required />
            <flux:textarea wire:model="address" label="Address" placeholder="Address" class="min-h-[80px]" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:input wire:model="phone" label="Phone" placeholder="Phone" type="tel" required />
            <flux:input wire:model="hireDate" label="Hire Date" placeholder="Hire Date" type="date" required />

            <flux:select label="Department" wire:model="selectedDepartmentId" wire:change="updatePositions" placeholder="Choose department..." required>
                @foreach ($departments as $department)
                    <flux:select.option value="{{ $department->id }}">{{ $department->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select label="Position" wire:model="selectedPositionId" placeholder="Choose position..." required>
                @foreach ($positions as $position)
                    <flux:select.option value="{{ $position->id }}">{{ $position->name }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <flux:separator class="my-2" />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:input wire:model="bankName" label="Bank Name" placeholder="Bank Name" required />
            <flux:input wire:model="bankAccount" label="Bank Account" placeholder="Bank Account" required />
            <flux:input wire:model="npwp" label="NPWP" placeholder="NPWP" />
            <flux:input wire:model="salary" label="Base Salary" placeholder="Base Salary" type="number" min="0" required />
            <flux:select wire:model="payFrequency" label="Pay Frequency" placeholder="Pay Frequency" required>
                <flux:select.option value="weekly">Weekly</flux:select.option>
                <flux:select.option value="monthly">Monthly</flux:select.option>
            </flux:select>
            <flux:input wire:model="effectiveDate" label="Effective Date" placeholder="Effective Date" type="date" required />
        </div>

        <div class="flex justify-end pt-4">
            <flux:button type="submit" variant="primary">Save</flux:button>
        </div>
    </form>
</flux:modal>

</div>
