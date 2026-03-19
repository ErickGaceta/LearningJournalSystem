<x-layouts::app :title="__('Monitoring')">
    <livewire:hr.monitoring-index />
    <x-pdf-preview-modal :url="url('hr/monitoring/documents')" event="open-document-preview" />
</x-layouts::app>