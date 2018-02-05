<div class="bg-white border-t border-b sm:rounded sm:border shadow-inner">
    <div class="flex mb-8 border-b border-grey-light pb-4">
        <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
            <div class="h-full flex justify-center items-center">
                @eyewitness_svg('notification-69', 'svgcolor-white')
            </div>
        </div>
        <h1 class="font-hairline ml-4 mt-2 text-2xl">Notifications</h1>
    </div>
    <div class="px-6 pb-4">
        @eyewitness_tutorial('All Eyewitness notifications are displayed here for '.config('eyewitness.days_to_keep_history').' days. You can change the length of time this notification history is kept for in the config file.')

        <div class="flex items-end justify-center w-full items-center pb-8">
            @if ($notifications->where('acknowledged', 0)->count())
                <div class="f-modal-alert pr-8">
                    <div class="f-modal-icon f-modal-warning scaleWarning">
                        <span class="f-modal-body pulseWarningIns"></span>
                        <span class="f-modal-dot pulseWarningIns"></span>
                    </div>
                </div>
                <p class="text-2xl text-grey-darker font-medium">Outstanding notifications</p>
            @else
                <div class="f-modal-alert pr-8">
                    <div class="f-modal-icon f-modal-success animate scaleWarning">
                        <span class="f-modal-line f-modal-tip animateSuccessTip"></span>
                        <span class="f-modal-line f-modal-long animateSuccessLong"></span>
                        <div class="f-modal-placeholder"></div>
                        <div class="f-modal-fix"></div>
                    </div>
                </div>
                <p class="text-2xl text-grey-darker font-medium">No outstanding notifications</p>
            @endif
        </div>

        <div class="py-8">
            @if (count($notifications))

                @foreach($notifications as $notification)
                    <div class="bg-grey-lighter rounded text-center text-grey-dark text-sm font-bold mb-8 px-2 md:px-4 py-1 shadow">
                        <div class="flex justify-between">
                            <div class="flex items-center">
                                <div class="flex-none bg-brand bg-circuit bg-md -mt-6 rounded shadow-lg text-white p-2">
                                    <div class="h-full flex justify-center items-center">
                                        {{ $notification->created_at->format('Y-m-d') }}
                                    </div>
                                </div>
                                <p class="hidden md:block text-sm ml-4">{{ $notification->title }}</p>
                            </div>
                            <div class="flex items-center">
                                <div class="font-mono rounded text-xs text-white {{ $notification->acknowledged ? 'bg-green' : 'bg-red' }} p-1 mr-1">
                                    {{ $notification->acknowledged ? 'Acknowledged' : 'Outstanding' }}
                                </div>
                                <eye-menu title='' color='svgcolor-grey'>
                                    <div slot="dropdown" class="bg-white shadow rounded border overflow-hidden" v-cloak>
                                        <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.notifications.show', $notification->id) }}" data-method="get">
                                            <div class="flex items-center">
                                                <div>
                                                    @eyewitness_svg('zoom-split', 'dropdown-menu-svg', 20, 20)
                                                </div>
                                                <div class="ml-3 -mt-1 font-normal">
                                                    View
                                                </div>
                                            </div>
                                        </button>
                                        @if (! $notification->acknowledged)
                                            <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.notifications.update', $notification->id) }}" data-method="put">
                                                <div class="flex items-center">
                                                    <div>
                                                        @eyewitness_svg('check-square-11', 'dropdown-menu-svg', 20, 20)
                                                    </div>
                                                    <div class="ml-3 -mt-1 font-normal">
                                                        Acknowledge
                                                    </div>
                                                </div>
                                            </button>
                                        @endif
                                    </div>
                                </eye-menu>
                            </div>
                        </div>
                        <div class="md:hidden text-left">
                            <p class="text-xs ml-4">{{ $notification->type }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center">
                    <p class="text-grey mb-6">If a notification is generated by your application it will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>
