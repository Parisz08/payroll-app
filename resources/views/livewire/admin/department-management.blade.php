<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-2xl bg-white dark:bg-neutral-900 p-6 shadow-2xl transition">
    <x-page-heading headingText="Department Management" descText="Manage your departments" />

    {{-- Add Department --}}
    <div class="flex justify-between items-center">
        <flux:modal.trigger name="add-department">
            <flux:button icon="plus" variant="primary" type="button" class="w-fit transition hover:scale-105 shadow-md">
                {{ __('Add Department') }}
            </flux:button>
        </flux:modal.trigger>
    </div>

    {{-- Modal Add Department --}}
    <flux:modal wire:close="closeModal" name="add-department" class="md:w-96">
        <form wire:submit="addDepartment" class="space-y-6">
            <div>
                <flux:heading size="lg">New Department</flux:heading>
                <flux:text class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
                    Add a new department to the system. This will allow you to manage your departments more effectively.
                </flux:text>
            </div>
            <flux:input wire:model="name" label="Name" placeholder="Department name" required />
            <flux:textarea wire:model="description" label="Description" placeholder="Department description" />
            <div class="flex justify-end pt-4 border-t dark:border-neutral-700">
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 dark:border-neutral-800 shadow-lg">
        <table class="w-full table-auto text-sm text-left text-gray-700 dark:text-neutral-300">
            <thead class="bg-gradient-to-r from-gray-100 to-gray-50 dark:from-neutral-800 dark:to-neutral-900 text-xs uppercase tracking-wider text-gray-600 dark:text-neutral-400 border-b dark:border-neutral-700">
                <tr>
                    <th class="p-5 w-12 font-bold">{{ __('No') }}</th>
                    <th class="p-5 font-bold">{{ __('Name') }}</th>
                    <th class="p-5 font-bold">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                @forelse ($departments as $department)
                    <tr class="hover:bg-blue-50 dark:hover:bg-neutral-800/40 transition">
                        <td class="px-5 py-4 font-medium text-center text-gray-900 dark:text-neutral-200">
                            {{ $loop->iteration + ($departments->currentPage() - 1) * $departments->perPage() }}
                        </td>
                        <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">
                            {{ $department->name }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex gap-2 flex-wrap">
                                <flux:button wire:click="openEditModal({{ $department->id }})"
                                    icon="pencil-square" variant="primary" type="button"
                                    class="transition hover:scale-105 shadow-sm">
                                    {{ __('Edit') }}
                                </flux:button>

                                <flux:button wire:click="openDeleteModal('{{ $department->id }}', '{{ $department->name }}')"
                                    icon="trash" variant="danger" type="button"
                                    class="transition hover:scale-105 shadow-sm">
                                    {{ __('Delete') }}
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-5 py-6 text-center text-sm italic text-gray-500 dark:text-neutral-400">
                            No departments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end pt-2">
        {{ $departments->links() }}
    </div>

    {{-- Modal Delete --}}
    <flux:modal name="delete-department" class="min-w-[22rem]" wire:close="closeModal">
        <form wire:submit="deleteDepartment" class="space-y-6">
            <div>
                <flux:heading size="lg">Delete
                    {{ $name }}

                    ?
                </flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this department.</p>
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

    {{-- Modal Edit Department --}}
    <flux:modal wire:close="closeModal" name="edit-department" class="md:w-96">
        <form wire:submit="updateDepartment" class="space-y-6">
            <div>
                <flux:heading size="lg">Update Department</flux:heading>
                <flux:text class="mt-2">
                    Update a department to the system.
                </flux:text>
            </div>
            <flux:input wire:model="name" label="Name" placeholder="Department name" required />
            <flux:textarea wire:model="description" label="Description" placeholder="Department description" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
