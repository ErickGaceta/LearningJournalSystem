<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $document->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-lg">Document Details</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Full Name</p>
                                <p class="font-medium">{{ $document->fullname }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Hours</p>
                                <p class="font-medium">{{ $document->hours }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Date</p>
                                <p class="font-medium">{{ $document->date->format('F d, Y') }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Venue</p>
                                <p class="font-medium">{{ $document->venue }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Registration Fee</p>
                                <p class="font-medium">{{ $document->registration_fee }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Travel Expenses</p>
                                <p class="font-medium">{{ $document->travel_expenses }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Topics</p>
                            <p class="font-medium">{{ $document->topics }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Insights</p>
                            <p class="font-medium">{{ $document->insights }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Application</p>
                            <p class="font-medium">{{ $document->application }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Challenges</p>
                            <p class="font-medium">{{ $document->challenges }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Appreciation</p>
                            <p class="font-medium">{{ $document->appreciation }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>