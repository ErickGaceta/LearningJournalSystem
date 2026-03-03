@if(session('success'))
<flux:callout variant="success" icon="check-circle">
    {{ session('success') }}
</flux:callout>
@endif

@if(session('error'))
<flux:callout variant="danger" icon="x-circle">
    {{ session('error') }}
</flux:callout>
@endif