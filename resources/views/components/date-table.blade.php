@props([
    'columns' => [],
    'rows' => [],
    'actions' => true,
    'emptyMessage' => 'No hay registros disponibles'
])

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th class="py-3 px-4 text-muted text-uppercase fw-semibold small">
                        {{ $column['label'] }}
                    </th>
                @endforeach
                @if($actions)
                    <th class="py-3 px-4 text-end text-muted text-uppercase fw-semibold small">
                        Acciones
                    </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
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
                        <td class="py-3 px-4 text-end">
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