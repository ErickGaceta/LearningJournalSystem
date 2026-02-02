<x-layouts::app :title="__('Positions Browser')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Main Form - wrapping all inputs -->
        <form action="{{ route('positions.store') }}" method="POST">
            @csrf
        </form>
    </div>
</x-layouts::app>