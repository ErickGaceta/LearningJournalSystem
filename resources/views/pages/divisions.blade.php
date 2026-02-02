<x-layouts::app :title="__('DOST CAR Learning Journal System - Admin')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Main Form - wrapping all inputs -->
        <form action="{{ route('documents.store') }}" method="POST">
            @csrf
        </form>
    </div>
</x-layouts::app>