<x-layouts::app :title="__('DOST CAR Learning Journal System')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Personal Information -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200">
                <div class="grid grid-flow-col grid-rows-2 gap-4">
                    <div class="flex flex-col justify-center items-center gap-2">
                        <p class="text-lg font-medium text-neutral-600 dark:text-neutral-400">Personal Information</p>
                    </div>
                    <div class="grid grid-cols-1 grid-rows-2 justify-items-start items-start p-2 gap-4">
                        <div>
                            <label for="employee_id" class="block mb-2.5 text-base font-medium text-heading">Employee ID</label>
                            <p id="employee_id" class="text-heading w-full text-sm mt-1 rounded-xl block px-3 py-2 shadow-lg">{{ auth()->user()->employee_id }}</p>
                        </div>
                        <div>
                            <label for="name" class="block mb-2.5 text-base font-medium text-heading">Name</label>
                            <p id="name" class="border-none text-heading w-full text-sm mt-1 rounded-xl block px-3 py-2 shadow-lg">
                                {{ auth()->user()->first_name }}
                                {{ auth()->user()->middle_name ? auth()->user()->middle_name . ' ' : '' }}
                                {{ auth()->user()->last_name }} 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200">
                <div class="flex flex-col justify-center items-center gap-2">
                    <p class="text-lg font-medium text-neutral-600 dark:text-neutral-400">L&D Program Information</p>
                </div>
                <form class="w-min p-4" action="">
                    <div class="grid gap-3 mb-3 md:grid-cols-2">
                        <div>
                            <label for="title" class="block mb-1 text-base font-medium text-heading">L&D Title</label>
                            <input type="text" id="title" class="bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm mt-1 rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs placeholder:text-body" placeholder="L&D Title" required />
                        </div>
                        <div>
                            <label for="hours" class="block mb-1 text-base font-medium text-heading">Number of L&D Hours</label>
                            <input type="number" id="hours" class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs placeholder:text-body" placeholder="L&D Hours" required />
                        </div>
                        <div>
                            <label for="date" class="block mb-1 text-base font-medium text-heading">Date</label>
                            <input type="date"
                                id="date"
                                name="date"
                                datepicker
                                datepicker-autohide
                                class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs placeholder:text-body"
                                placeholder="Select date"
                                required />
                        </div>
                    </div>
                </form>
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
                </label>
                <textarea 
                    rows="3"
                    placeholder="Enter your answer..."
                    class="w-full px-4 py-3 rounded-lg border border-white bg-black text-white placeholder-gray-400 focus:ring-2 focus:ring-white focus:border-transparent resize-none"
                ></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-2">
                    B. I gained the following insights and discoveries...(understanding, perception, awareness)
                </label>
                <textarea 
                    rows="3"
                    placeholder="Enter your answer..."
                    class="w-full px-4 py-3 rounded-lg border border-white bg-black text-white placeholder-gray-400 focus:ring-2 focus:ring-white focus:border-transparent resize-none"
                ></textarea>
            </div>

            <div>
                 <label class="block text-sm font-medium text-white mb-2">
                    C. I will apply the new learnings in my current function by doing the following...
                </label>
                <textarea 
                    rows="3"
                    placeholder="Enter your answer..."
                    class="w-full px-4 py-3 rounded-lg border border-white bg-black text-white placeholder-gray-400 focus:ring-2 focus:ring-white focus:border-transparent resize-none"
                ></textarea>
            </div>

            <div>
                 <label class="block text-sm font-medium text-white mb-2">
                    D. I was most challenged on...
                </label>
                <textarea 
                    rows="3"
                    placeholder="Enter your answer..."
                    class="w-full px-4 py-3 rounded-lg border border-white bg-black text-white placeholder-gray-400 focus:ring-2 focus:ring-white focus:border-transparent resize-none"
                ></textarea>
            </div>

            <div>
                 <label class="block text-sm font-medium text-white mb-2">
                    E. I appreciated the...(Feedback: for management and services of HRD)
                </label>
                <textarea 
                    rows="3"
                    placeholder="Enter your answer..."
                    class="w-full px-4 py-3 rounded-lg border border-white bg-black text-white placeholder-gray-400 focus:ring-2 focus:ring-white focus:border-transparent resize-none"
                ></textarea>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <div class="w-80">
                <label class="block text-sm font-medium text-white mb-2">
                    Signature
                </label>
                <div class="border-2 border-dashed border-white rounded-lg p-4 bg-black">
                    <canvas 
                        id="signature-pad"
                        class="w-full h-24 cursor-crosshair"
                    ></canvas>
                    <button 
                        type="button"
                        class="mt-2 text-sm text-white hover:underline"
                    >
                        Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-layouts::app>