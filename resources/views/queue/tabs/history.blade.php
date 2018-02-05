<div class="bg-white border-t border-b sm:rounded sm:border shadow-inner mb-12">
    <div class="flex mb-8 border-b border-grey-light pb-4">
        <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
            <div class="h-full flex justify-center items-center">
                @eyewitness_svg('timeline', 'svgcolor-white')
            </div>
        </div>

        <h1 class="font-hairline ml-4 mt-2 text-2xl">History for <span class="font-normal">{{ $queue->connection }} - {{ $queue->tube }}</span></h1>
    </div>

    <div class="px-6 pb-4">
        @eyewitness_tutorial('This gives you a visual and trend display of how your queue has been performing recently. Each graph gives another piece of the puzzle, and together form an overall view of the performance of this queue.')
    </div>

    <div class="block lg:flex lg:flex-wrap">
        <div class="w-full lg:w-1/2 flex-none px-4 lg:px-0">
            <div class="bg-brand my-6 pb-4 md:pb-0 md:mx-6 md:rounded shadow-lg text-white font-hairline">
                <div class="text-center">
                    <h4 class="text-white font-normal tracking-wide pt-4">Total processed jobs per day</h4>
                </div>
                <div class="ct-queue-total md:p-6"></div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex-none px-4 lg:px-0">
            <div class="bg-brand my-6 pb-4 md:pb-0 md:mx-6 md:rounded shadow-lg text-white font-hairline">
                <div class="text-center">
                    <h4 class="text-white font-normal tracking-wide pt-4">Average job wait time</h4>
                </div>
                <div class="ct-queue-wait md:p-6"></div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex-none px-4 lg:px-0">
            <div class="bg-brand my-6 pb-4 md:pb-0 md:mx-6 md:rounded shadow-lg text-white font-hairline">
                <div class="text-center">
                    <h4 class="text-white font-normal tracking-wide pt-4">Average job process time</h4>
                </div>
                <div class="ct-queue-process md:p-6"></div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex-none px-4 lg:px-0">
            <div class="bg-brand my-6 pb-4 md:pb-0 md:mx-6 md:rounded shadow-lg text-white font-hairline">
                <div class="text-center">
                    <h4 class="text-white font-normal tracking-wide pt-4">Average number of pending jobs</h4>
                </div>
                <div class="ct-queue-pending md:p-6"></div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex-none px-4 lg:px-0">
            <div class="bg-brand my-6 pb-4 md:pb-0 md:mx-6 md:rounded shadow-lg text-white font-hairline">
                <div class="text-center">
                    <h4 class="text-white font-normal tracking-wide pt-4">Total worker idle time</h4>
                </div>
                <div class="ct-queue-idle md:p-6"></div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex-none px-4 lg:px-0">
            <div class="bg-brand my-6 pb-4 md:pb-0 md:mx-6 md:rounded shadow-lg text-white font-hairline">
                <div class="text-center">
                    <h4 class="text-white font-normal tracking-wide pt-4">Total job exceptions</h4>
                </div>
                <div class="ct-queue-exception md:p-6"></div>
            </div>
        </div>
    </div>
</div>
