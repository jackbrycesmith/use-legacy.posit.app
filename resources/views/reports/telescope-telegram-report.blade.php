@php
$entries = $entries ?? collect();

$entriesGroupedByType = $entries->groupBy('type');

@endphp
🚨 New entries found 🚨

@foreach ($entriesGroupedByType as $type => $entries)
<b>{{ $type }}</b> x {{ $entries->count() }}
@endforeach
