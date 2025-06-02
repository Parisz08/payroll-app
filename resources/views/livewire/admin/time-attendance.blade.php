<div>
    <x-page-heading headingText="Time and Attendances" descText="Manage your time and attendances" />

    {{-- Month Filter Buttons --}}
    <div class="my-6 flex flex-wrap items-center gap-3">
        <span class="text-sm font-semibold text-gray-700 dark:text-white">📅 Filter by Month:</span>
        <button wire:click="clearMonthFilter"
                class="px-4 py-1.5 rounded-full text-sm font-semibold shadow transition
                {{ !$selectedYearMonthFilter 
                    ? 'bg-blue-600 text-white hover:bg-blue-700' 
                    : 'bg-white dark:bg-neutral-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-neutral-600 hover:bg-gray-100 dark:hover:bg-neutral-700' }}">
            Show All
        </button>
        @foreach ($monthLinks as $link)
            <button wire:click="applyMonthFilter('{{ $link['value'] }}')"
                    class="px-4 py-1.5 rounded-full text-sm font-semibold shadow transition
                    {{ $selectedYearMonthFilter == $link['value'] 
                        ? 'bg-indigo-600 text-white hover:bg-indigo-700' 
                        : 'bg-white dark:bg-neutral-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-neutral-600 hover:bg-gray-100 dark:hover:bg-neutral-700' }}">
                {{ $link['display'] }}
            </button>
        @endforeach
        @empty($monthLinks)
            <p class="text-xs text-gray-500 dark:text-neutral-400 italic">No specific months available.</p>
        @endempty
    </div>

    {{-- Attendances Table --}}
    <div class="overflow-x-auto rounded-2xl shadow-lg ring-1 ring-black/10 dark:ring-white/10 bg-white dark:bg-neutral-900">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 text-sm">
            <thead class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-neutral-800 dark:to-neutral-900">
                <tr>
                    @foreach(['Employee', 'Date', 'Clock In', 'Clock Out'] as $header)
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse ($attendances as $attendance)
                    <tr class="hover:bg-blue-50 dark:hover:bg-neutral-800/50 transition">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $attendance->employee->full_name }}</td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ optional($attendance->attendance_date)->format('d M Y') ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ optional($attendance->check_in)->format('H:i:s') ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ optional($attendance->check_out)->format('H:i:s') ?? 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center italic text-gray-400 dark:text-neutral-500">
                            No attendances found{{ $selectedYearMonthFilter ? ' for ' . \Carbon\Carbon::createFromFormat('Y-m', $selectedYearMonthFilter)->format('F Y') : '' }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $attendances->links(data: ['scrollTo' => false]) }}
    </div>

    <flux:separator />
    <flux:heading size="xl" class="mt-10 mb-4">Overtimes</flux:heading>

    <flux:button icon="plus" variant="primary" type="button" class="w-fit transition duration-200 hover:scale-105 shadow-md" wire:click="openOvertimeModal">
        {{ __('Add Overtime') }}
    </flux:button>

    <div class="w-full overflow-x-auto mt-6 rounded-2xl shadow-lg ring-1 ring-black/10 dark:ring-white/10 bg-white dark:bg-neutral-900">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 text-sm">
            <thead class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-neutral-800 dark:to-neutral-900">
                <tr>
                    @foreach(['Employee', 'Date - Time', 'Duration', 'Reason', 'Actions'] as $header)
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($overtimes as $overtime)
                    <tr class="hover:bg-indigo-50 dark:hover:bg-neutral-800/40 transition">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $overtime->employee->full_name }}
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($overtime->overtime_date)->format('d M Y') ?? 'N/A' }}
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($overtime->start_time)->format('H:i') ?? 'N/A' }} - {{ \Carbon\Carbon::parse($overtime->end_time)->format('H:i') ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            {{ $overtime->duration }} minutes
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300 max-w-xs truncate" title="{{ $overtime->reason }}">
                            {{ $overtime->reason }}
                        </td>
                        <td class="px-6 py-4 text-right flex gap-2">
                            <flux:button icon="pencil" variant="primary" type="button" class="hover:scale-105" wire:click="openOvertimeModal({{ $overtime->id }})">
                                {{ __('Edit') }}
                            </flux:button>
                            <flux:button icon="trash" variant="danger" type="button" class="hover:scale-105" wire:click="openDeleteOvertimeModal({{ $overtime->id }})">
                                {{ __('Delete') }}
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center italic text-gray-400 dark:text-neutral-500">
                            No overtimes found{{ $selectedYearMonthFilter ? ' for ' . \Carbon\Carbon::createFromFormat('Y-m', $selectedYearMonthFilter)->format('F Y') : '' }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Overtime Modal --}}
    <flux:modal wire:close="closeModal" name="overtime-modal" class="md:w-96">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">
                    @if ($isEditting)
                        Edit
                    @else
                        New
                    @endif Overtime
                </flux:heading>
                <flux:text class="mt-2">
                    @if ($isEditting)
                        Update overtime to the system. This will allow you to manage your overtime more effectively.
                    @else
                        Add a new overtime to the system. This will allow you to manage your overtime more effectively.
                    @endif
                </flux:text>
            </div>
            
            <flux:select label="Employee" wire:model="selectedEmployeeId" placeholder="Choose employee..." required>
                @foreach ($employees as $employee)
                    <flux:select.option value="{{ $employee->id }}">{{ $employee->full_name }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:input wire:model="overtimeDate" label="Overtime Date" type="date" required />
            <flux:input wire:model="startTime" label="Start Time" type="time" required />
            <flux:input wire:model="endTime" label="End Time" type="time" required />
            <flux:textarea wire:model="reason" label="Reason" placeholder="Reason" required />

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>
    {{-- Modal Delete --}}
    <flux:modal name="delete-modal" class="min-w-[22rem]" wire:close="closeModal">
        <form 
            wire:submit="deleteOvertime"
        class="space-y-6">
            <div>
                <flux:heading size="lg">Delete
                    {{-- {{ $name }} --}}
                    ?
                </flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this overtime.</p>
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
