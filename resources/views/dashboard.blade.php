<x-layouts::app :title="__('Learning Journal System')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
          <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-black dark:bg-neutral-800">
    <div class="p-6 flex items-center justify-center min-h-[400px]">
        <div class="w-full max-w-md text-center">
        <h3 class="text-xl font-semibold text-white mb-6">
            Employee Details
        </h3>

        <div class="space-y-4 text-left">

            <div>
                <input
                    type="text"
                    id="name"
                    placeholder="Employee Name"
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-600 bg-neutral-900 dark:bg-neutral-800 text-white dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>

            <div>
                <input
                    type="text"
                    id="employee_id"
                    placeholder="Employee ID Number"
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-600 bg-neutral-900 dark:bg-neutral-800 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>
        </div>
        </div>
    </div>
</div>
             <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-black dark:bg-neutral-800">
    <div class="p-6 flex items-center justify-center min-h-[400px]">
        <div class="w-full max-w-md text-center">
        <h3 class="text-xl font-semibold text-white mb-6">
            L&D Program Attendance Details
        </h3>

        <div class="space-y-4 text-left">

            <div>
                <input
                    type="text"
                    id="title_of_l&d_program_attended"
                    placeholder="Title of L&D Program Attended"
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-600 bg-neutral-900 dark:bg-neutral-800 text-white dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>

            <div>
                <input
                    type="text"
                    id="no._of_l&d"
                    placeholder="No. of L&D Hours"
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-600 bg-neutral-900 dark:bg-neutral-800 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>
        </div>
        </div>
    </div>
</div>
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-black dark:bg-neutral-800">
    <div class="p-6 flex items-center justify-center min-h-[400px]">
        <div class="w-full max-w-md text-center">
        <h3 class="text-xl font-semibold text-white mb-6">
            Program Expenses
        </h3>

        <div class="space-y-4 text-left">

            <div>
                <input
                    type="text"
                    id="registration_fee"
                    placeholder="Registration Fee"
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-600 bg-neutral-900 dark:bg-neutral-800 text-white dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>

            <div>
                <input
                    type="text"
                    id="travel_expensesss"
                    placeholder="Travel Expenses"
                    class="w-full px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-600 bg-neutral-900 dark:bg-neutral-800 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>
        </div>
        </div>
    </div>
</div>
    </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-white bg-black">
    <x-placeholder-pattern class="absolute inset-0 size-full stroke-white/20" />

    <div class="relative z-10 p-8 h-full flex flex-col">
        <div class="flex-1 space-y-6">
           <div>
    <label class="block text-sm font-medium text-white mb-2">
        A. I learned the following from the L&D program I attended...(knowledge, skills, attitude, information) Please indicate topic/topics.
        <span class="text-red-500">*</span>
    </label>
    <textarea
        id="feedbackTextarea"
        name="feedback"
        rows="3"
        placeholder="Enter your answer..."
        class="w-full px-4 py-3 rounded-lg border border-white bg-black text-white placeholder-gray-400 focus:ring-2 focus:ring-white focus:border-transparent resize-none"
        required
    ></textarea>
    <span id="feedbackError" class="text-red-500 text-sm hidden">This field is required</span>
</div>

            <div>
    <label class="block text-sm font-medium text-white mb-2">
        B. I gained the following insights and discoveries...(understanding, perception, awareness)
        <span class="text-red-500">*</span>
    </label>
    <textarea
        id="feedbackTextarea"
        name="feedback"
        rows="3"
        placeholder="Enter your answer..."
        class="w-full px-4 py-3 rounded-lg border border-white bg-black text-white placeholder-gray-400 focus:ring-2 focus:ring-white focus:border-transparent resize-none"
        required
    ></textarea>
    <span id="feedbackError" class="text-red-500 text-sm hidden">This field is required</span>
</div>

            <div>
    <label class="block text-sm font-medium text-white mb-2">
        C. I will apply the new learnings in my current function by doing the following...
        <span class="text-red-500">*</span>
    </label>
    <textarea
        id="feedbackTextarea"
        name="feedback"
        rows="3"
        placeholder="Enter your answer..."
        class="w-full px-4 py-3 rounded-lg border border-white bg-black text-white placeholder-gray-400 focus:ring-2 focus:ring-white focus:border-transparent resize-none"
        required
    ></textarea>
    <span id="feedbackError" class="text-red-500 text-sm hidden">This field is required</span>
</div>

            <div>
    <label class="block text-sm font-medium text-white mb-2">
        D. I was most challenged on...
        <span class="text-red-500">*</span>
    </label>
    <textarea
        id="feedbackTextarea"
        name="feedback"
        rows="3"
        placeholder="Enter your answer..."
        class="w-full px-4 py-3 rounded-lg border border-white bg-black text-white placeholder-gray-400 focus:ring-2 focus:ring-white focus:border-transparent resize-none"
        required
    ></textarea>
    <span id="feedbackError" class="text-red-500 text-sm hidden">This field is required</span>
</div>

            <div>
    <label class="block text-sm font-medium text-white mb-2">
        E. I appreciated the...(Feedback: for management and services of HRD)
        <span class="text-red-500">*</span>
    </label>
    <textarea
        id="feedbackTextarea"
        name="feedback"
        rows="3"
        placeholder="Enter your answer..."
        class="w-full px-4 py-3 rounded-lg border border-white bg-black text-white placeholder-gray-400 focus:ring-2 focus:ring-white focus:border-transparent resize-none"
        required
    ></textarea>
    <span id="feedbackError" class="text-red-500 text-sm hidden">This field is required</span>
</div>

<div class="mt-8 flex justify-end">
    <button
        type="button"
        onclick="handleSave()"
        class="mt-2 px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 border border-blue-500 rounded-md transition-colors duration-200 cursor-pointer"
    >
        Save
    </button>
</div>

<script>
function handleSave() {
    const textarea = document.getElementById('feedbackTextarea');
    const errorMessage = document.getElementById('feedbackError');

    // Remove any previous error styling
    textarea.classList.remove('border-red-500');
    errorMessage.classList.add('hidden');

    // Check if textarea is empty
    if (textarea.value.trim() === '') {
        // Show error message
        errorMessage.classList.remove('hidden');
        textarea.classList.add('border-red-500');

        // Alert user
        alert('Please fill in the required feedback field');

        // Focus on the textarea
        textarea.focus();

        return false;
    }

    // If validation passes, proceed with save
    alert('Your Document has been saved');
    console.log('Save button clicked');
    console.log('Feedback:', textarea.value);
}
</script>
    </div>
</div>
</x-layouts::app>
