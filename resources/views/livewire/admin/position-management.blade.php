<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl bg-white dark:bg-neutral-900 p-6 shadow-lg transition">
    <x-page-heading headingText="Position Management" descText="Manage your positions" />

    {{-- Add Position --}}
    <div class="flex justify-between items-center">
        <flux:modal.trigger name="position">
            <flux:button icon="plus" variant="primary" type="button" class="w-fit">
                {{ __('Add Positions') }}
            </flux:button>
        </flux:modal.trigger>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-neutral-700 shadow-sm">
        <table class="w-full table-auto text-sm text-left text-gray-700 dark:text-neutral-300">
            <thead class="bg-gray-50 dark:bg-neutral-800 text-xs uppercase tracking-wider text-gray-600 dark:text-neutral-400 border-b dark:border-neutral-700">
                <tr>
                    <th class="p-4 w-12 font-semibold">{{ __('No') }}</th>
                    <th class="p-4 font-semibold">{{ __('Name') }}</th>
                    <th class="p-4 font-semibold">{{ __('Department') }}</th>
                    <th class="p-4 font-semibold">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                @forelse ($positions as $position)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/30 transition">
                        <td class="px-4 py-3">{{ $loop->iteration + ($positions->currentPage() - 1) * $positions->perPage() }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-neutral-100">{{ $position->name }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-neutral-300">{{ $position->department->name }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2 flex-wrap">
                                <flux:button wire:click="openEditModal({{ $position->id }})" icon="pencil-square"
                                    variant="primary" type="button">
                                    {{ __('Edit') }}
                                </flux:button>

                                <flux:button
                                    wire:click="openDeleteModal('{{ $position->id }}', '{{ $position->name }}')"
                                    icon="trash" variant="danger" type="button">
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-neutral-400 italic">
                            No positions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end">
        {{ $positions->links() }}
</div>

    {{-- Modal Add / Edit Positions --}}
    <flux:modal wire:close="closeModal" name="position" class="md:w-96">
        <form @if($isEditting) wire:submit="updatePosition" @else wire:submit="addPosition" @endif class="space-y-6">
            <div>
                <flux:heading size="lg">@if($isEditting) Edit @else New @endif Position</flux:heading>
                <flux:text class="mt-2">
                    @if ($isEditting)
                    Update a position to the system.
                    @else
                    Add a new position to the system.
                    @endif
                </flux:text>
            </div>
            <flux:input wire:model="name" label="Name" placeholder="Position name" required />
            <flux:textarea wire:model="description" label="Description" placeholder="Position description" />
            <flux:select label="Department" wire:model="selectedDepartmentId" placeholder="Choose department..."
                required>
                @foreach ($departments as $department)
                    <flux:select.option value="{{ $department->id }}">{{ $department->name }}</flux:select.option>
                @endforeach
            </flux:select>
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Modal Delete --}}
    <flux:modal name="delete-position" class="min-w-[22rem]" wire:close="closeModal">
        <form wire:submit="deletePosition" class="space-y-6">
            <div>
                <flux:heading size="lg">Delete
                    {{ $name }}

                    ?
                </flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this position.</p>
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
