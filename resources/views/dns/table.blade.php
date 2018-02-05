<table class="w-full" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th class="text-center text-brand text-xl font-thin border-b py-2">Type</th>
            <th class="text-center text-brand text-xl font-thin border-b py-2">Host</th>
            <th class="text-center text-brand text-xl font-thin border-b py-2">Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach($table['record'] as $record)
            <tr>
                <td class="text-center text-sm py-3 font-hairline border-b px-2"><span class="rounded py-1 px-2 uppercase font-semibold text-white text-sm bg-blue">{{ isset($record['type']) ? $record['type'] : '??' }}</span></td>

                <td class="text-center text-sm py-3 font-hairline border-b px-2">{{ isset($record['host']) ? $record['host'] : '??' }}</td>
                <td class="text-center text-sm py-3 font-hairline border-b px-2">
                    @foreach ($record as $id => $value)
                        @if (! in_array($id, ['type', 'host', 'class']))
                            @if (is_array($value))
                                @foreach($value as $x)
                                    <strong>{{ $id }}:</strong> {{ $x }}<br/>
                                @endforeach
                            @else
                                <strong>{{ $id }}:</strong> {{ $value }}<br/>
                            @endif
                        @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" class="text-right text-xs py-3 font-hairline px-2"><em>Record created: {{ $table['created_at']->format('Y-m-d H:i:s') }}</em></td>
        </tr>
    </tbody>
</table>
