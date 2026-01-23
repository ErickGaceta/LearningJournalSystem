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
</div>
</x-layouts::app>
