@props([
    'columns' => [],
    'rows' => [],
    'actions' => true,
    'emptyMessage' => 'No hay registros disponibles'
])

<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
            <tr>
                @foreach($columns as $column)
                    <th class="py-3 px-4 text-left font-semibold">
                        {{ $column['label'] }}
                    </th>
                @endforeach
                @if($actions)
                    <th class="py-3 px-4 text-right font-semibold">
                        Acciones
                    </th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rows as $row)
                <tr class="hover:bg-gray-50">
                    @foreach($columns as $key => $column)
                        <td class="py-3 px-4">
                            @if(isset($column['render']))
                                {!! $column['render']($row) !!}
                            @else
                                {{ data_get($row, $key) }}
                            @endif
                        </td>
                    @endforeach
                    @if($actions)
                        <td class="py-3 px-4 text-right">
                            {{ $actionsSlot ?? '' }}
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + ($actions ? 1 : 0) }}" class="p-4">
                        <x-empty-state :message="$emptyMessage" />
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>