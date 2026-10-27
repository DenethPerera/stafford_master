@props(['headers'])

<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table table-hover align-middle']) }}>
        <thead class="table-light">
            <tr class="small text-uppercase text-muted">
                @foreach($headers as $header)
                    <th class="px-3 py-3 fw-bold">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
