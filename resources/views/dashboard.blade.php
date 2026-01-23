<x-layouts::app :title="__('DOST CAR Learning Journal System')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Personal Information -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200">
                <div class="grid grid-flow-col grid-rows-2 gap-4">
                    <div class="flex flex-col justify-center items-center gap-2">
                        <p class="text-lg font-medium text-neutral-600 dark:text-neutral-400">Personal Information</p>
                    </div>
                    <div class="grid grid-cols-2 grid-rows-2 justify-items-start items-start p-2 gap-4">
                        <p class="text-base text-neutral-500 dark:text-neutral-500">Employee ID:</p>
                        <p class="text-base font-medium text-neutral-900 dark:text-white">{{ auth()->user()->employee_id }}</p>
                        <p class="text-base text-neutral-500 dark:text-neutral-500">Name:</p>
                        <p class="text-base font-medium text-neutral-900 dark:text-white">
                            {{ auth()->user()->first_name }}
                            {{ auth()->user()->middle_name ? auth()->user()->middle_name . ' ' : '' }}
                            {{ auth()->user()->last_name }}
                        </p>p>
                    </div>
                </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200">
                <div class="flex h-full flex-col justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Active Users</p>
                        <p class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">456</p>
                    </div>
                    <p class="text-xs text-neutral-500 dark:text-neutral-500">↑ 8% from last month</p>
                </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200">
                <div class="flex h-full flex-col justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Completed Tasks</p>
                        <p class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">89%</p>
                    </div>
                    <p class="text-xs text-neutral-500 dark:text-neutral-500">↑ 3% from last month</p>
                </div>
            </div>
        </div>

        <!-- Main Content Area with Form -->
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200">
            <h2 class="mb-6 text-2xl font-bold text-neutral-900 dark:text-white">Create New Journal Entry</h2>

            <form class="space-y-6">
                <!-- Title Input -->
                <div>
                    <label for="title" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Journal Title
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-neutral-900 placeholder-neutral-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 dark:focus:border-blue-400"
                        placeholder="Enter your journal title">
                </div>

                <!-- Category Select -->
                <div>
                    <label for="category" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Category
                    </label>
                    <select
                        id="category"
                        name="category"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-neutral-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-blue-400">
                        <option value="">Select a category</option>
                        <option value="research">Research</option>
                        <option value="development">Development</option>
                        <option value="training">Training</option>
                        <option value="meeting">Meeting</option>
                    </select>
                </div>

                <!-- Description Textarea -->
                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-neutral-900 placeholder-neutral-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 dark:focus:border-blue-400"
                        placeholder="Write your journal entry here..."></textarea>
                </div>

                <!-- Date Input -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="date" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Date
                        </label>
                        <input
                            type="date"
                            id="date"
                            name="date"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-neutral-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-blue-400">
                    </div>

                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Status
                        </label>
                        <select
                            id="status"
                            name="status"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-neutral-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-blue-400">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>

                <!-- Checkbox -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="featured"
                        name="featured"
                        class="h-4 w-4 rounded border-neutral-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-900">
                    <label for="featured" class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">
                        Mark as featured entry
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800">
                        Save Journal Entry
                    </button>
                    <button
                        type="button"
                        class="rounded-lg border border-neutral-300 bg-white px-6 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:ring-offset-neutral-800">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>