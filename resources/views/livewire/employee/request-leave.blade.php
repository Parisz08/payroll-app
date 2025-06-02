<div>
    <x-page-heading headingText="Request Leave" descText="Manage your leave requests and approvals" />

    <flux:button icon="plus" variant="primary" type="button" class="w-fit hover:scale-105 transition" wire:click="openModal">
        {{ __('Request Leave') }}
    </flux:button>

    <div class="w-full overflow-x-auto mt-6">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow-xl rounded-2xl ring-1 ring-gray-300 dark:ring-neutral-700 bg-white dark:bg-neutral-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-100 dark:bg-neutral-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-neutral-400 uppercase tracking-wider">
                                Leave Type
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-neutral-400 uppercase tracking-wider">
                                From - To
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-neutral-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-neutral-400 uppercase tracking-wider">
                                Created At
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-neutral-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-100 dark:divide-neutral-800">
                        @forelse ($leaveRequests as $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/40 transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800 dark:text-white capitalize">
                                    {{ $data->leave_type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-neutral-400">
                                    {{ \Carbon\Carbon::parse($data->start_date)->format('d M Y') }} –
                                    {{ \Carbon\Carbon::parse($data->end_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($data->status == 'pending')
                                        <flux:badge variant="pill" color="yellow">Pending</flux:badge>
                                    @elseif ($data->status == 'approved')
                                        <flux:badge variant="pill" color="green">Approved</flux:badge>
                                    @elseif ($data->status == 'rejected')
                                        <flux:badge variant="pill" color="red">Rejected</flux:badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-neutral-400">
                                    {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                                    <flux:button icon="eye" variant="filled" type="button" class="transition hover:scale-105"
                                        wire:click="openViewModal({{ $data->id }})">
                                        {{ __('View') }}
                                    </flux:button>
                                    @if ($data->status == 'pending')
                                        <flux:button icon="pencil" variant="primary" type="button" class="transition hover:scale-105"
                                            wire:click="openModal({{ $data->id }})">
                                            {{ __('Edit') }}
                                        </flux:button>
                                    @endif
                                    <flux:button icon="trash" variant="danger" type="button" class="transition hover:scale-105"
                                        wire:click="openDeleteModal({{ $data->id }})">
                                        {{ __('Delete') }}
                                    </flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-sm italic text-gray-500 dark:text-neutral-400">
                                    You haven't made any leave requests yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
                    @endif Leave Request
                </flux:heading>
                <flux:text class="mt-2">
                    @if ($isEditting)
                        You're about to edit this leave request.
                    @else
                        You're about to create a new leave request.
                    @endif
                </flux:text>
            </div>

            <flux:select label="Leave Type" wire:model="leaveType" placeholder="Choose Leave Type..." required>
                <flux:select.option value="sick">Sick</flux:select.option>
                <flux:select.option value="vacation">Vacation</flux:select.option>
                <flux:select.option value="personal">Personal</flux:select.option>
                <flux:select.option value="other">Other</flux:select.option>
            </flux:select>
            <flux:input wire:model="startDate" label="Start Date" type="date" required />
            <flux:input wire:model="endDate" label="End Date" type="date" required />
            <flux:textarea wire:model="reason" label="Reason" placeholder="Reason" required />

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Modal Delete --}}
    <flux:modal name="delete-modal" class="min-w-[22rem]" wire:close="closeModal">
        <form wire:submit="deleteLeaveRequest" class="space-y-6">
            <div>
                <flux:heading size="lg">Delete
                    {{-- {{ $name }} --}}
                    ?
                </flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this leave request.</p>
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

    <flux:modal name="view-modal" class="min-w-[22rem]" wire:close="closeModal">
        <flux:heading size="lg">Leave Request Details</flux:heading>
        <flux:text class="mt-2">
            Here's the details of your leave request.
        </flux:text>
        <flux:separator class="my-4" />

        @if ($leaveRequestData)
            <div class="mt-4 space-y-2">
                <flux:heading class="mt-2">Leave Type</flux:heading>
                <flux:text class="mt-2 capitalize">
                    {{ $leaveRequestData->leave_type }}
                </flux:text>

                <flux:heading class="mt-2">From - To</flux:heading>
                <flux:text>
                    {{ \Carbon\Carbon::parse($leaveRequestData->start_date)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($leaveRequestData->end_date)->format('d M Y') }}
                </flux:text>

                <flux:heading class="mt-2">Status</flux:heading>
                <flux:text>
                    @if ($leaveRequestData->status == 'pending')
                    <flux:badge variant="pill" color="yellow">Pending</flux:badge>
                    @elseif ($leaveRequestData->status == 'approved')
                    <flux:badge variant="pill" color="green">Approved</flux:badge>
                    @elseif ($leaveRequestData->status == 'rejected')
                    <flux:badge variant="pill" color="red">Rejected</flux:badge>
                    @endif
                </flux:text>
                @if ($leaveRequestData->status == 'approved')
                    <flux:heading class="mt-2">Approval Date</flux:heading>
                    <flux:text>
                        {{ \Carbon\Carbon::parse($leaveRequestData->approved_date)->format('d M Y - H:i') }}
                    </flux:text>
                    
                @endif
                <flux:heading class="mt-2">Reason</flux:heading>
                <flux:text class="capitalize">
                    {{ $leaveRequestData->reason }}
                </flux:text>
                <flux:heading class="mt-2">Created At</flux:heading>
                <flux:text>
                    {{ \Carbon\Carbon::parse($leaveRequestData->created_at)->format('d M Y') }}
                </flux:text>
            </div>
        @endif
    </flux:modal>
</div>
