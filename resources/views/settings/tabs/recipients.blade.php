<div class="bg-white border-t border-b sm:rounded sm:border shadow-inner">
    <div class="flex mb-8 border-b border-grey-light pb-4">
        <div class="flex-none bg-brand bg-circuit bg-lg ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
            <div class="h-full flex justify-center items-center">
                @eyewitness_svg('multiple-11', 'svgcolor-white')
            </div>
        </div>
        <h1 class="font-hairline ml-4 mt-2 text-2xl">Notification recipients</h1>
    </div>
    <div class="text-center px-2 md:px-6 pb-4">
        @eyewitness_tutorial('Here are the list of recipients for any alert notifications sent by Eyewitness. You can see the level of severity that the recipient will be notified of. You can also send a test notification to ensure it is working as intended.')

        <div class="py-8">
            @if (count($recipients))
                @foreach($recipients as $recipient)
                    <div class="bg-grey-lighter rounded text-center text-grey-dark text-sm font-bold mb-8 px-2 md:px-4 py-1 shadow">
                        <div class="flex justify-between">
                            <div class="flex items-center">
                                <div class="bg-transparent -mt-6 w-8 h-8 md:w-10 md:h-10">
                                    <div class="h-full flex justify-center items-center shadow-lg rounded-lg">
                                        @if ($recipient->type === 'email')
                                            @eyewitness_img('email.png')
                                        @elseif ($recipient->type === 'slack')
                                            @eyewitness_img('slack.png')
                                        @elseif ($recipient->type === 'pushover')
                                            @eyewitness_img('pushover.png')
                                        @elseif ($recipient->type === 'pagerduty')
                                            @eyewitness_img('pagerduty.png')
                                        @elseif ($recipient->type === 'webhook')
                                            @eyewitness_img('webhook.png')
                                        @elseif ($recipient->type === 'nexmo')
                                            @eyewitness_img('nexmo.png')
                                        @elseif ($recipient->type === 'hipchat')
                                            @eyewitness_img('hipchat.png')
                                        @endif
                                    </div>
                                </div>
                                <p class="hidden md:block text-sm ml-4">{{ $recipient->address }}</p>
                            </div>
                            <div class="flex items-center">
                                <div class="font-mono rounded text-xs text-white {{ $recipient->low ? 'bg-green' : 'bg-grey-darkest' }} px-1 mr-1">
                                    L
                                </div>
                                <div class="font-mono rounded text-xs text-white {{ $recipient->medium ? 'bg-green' : 'bg-grey-darkest' }} px-1 mr-1">
                                    M
                                </div>
                                <div class="font-mono rounded text-xs text-white {{ $recipient->high ? 'bg-green' : 'bg-grey-darkest' }} px-1 mr-2">
                                    H
                                </div>
                                <eye-menu title='' color='svgcolor-grey'>
                                    <div slot="dropdown" class="bg-white shadow rounded border overflow-hidden" v-cloak>
                                        <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.recipients.test', $recipient->id) }}" data-method="post">
                                            <div class="flex items-center">
                                                <div>
                                                    @eyewitness_svg('lab', 'dropdown-menu-svg', 20, 20)
                                                </div>
                                                <div class="ml-3 -mt-1 font-normal">
                                                    Test
                                                </div>
                                            </div>
                                        </button>
                                        <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.recipients.destroy', $recipient->id) }}" data-method="delete">
                                            <div class="flex items-center">
                                                <div>
                                                    @eyewitness_svg('trash', 'dropdown-menu-svg', 20, 20)
                                                </div>
                                                <div class="ml-3 -mt-1 font-normal">
                                                    Delete
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </eye-menu>
                            </div>
                        </div>
                        <div class="md:hidden text-left">
                            <p class="text-xs ml-4">{{ $recipient->address }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="mb-4">
                    @eyewitness_svg('wifi-off')
                </div>
                <p class="text-2xl text-grey-darker font-medium mb-4">No recipients found.</p>
                <p class="text-grey max-w-xs mx-auto mb-6">You should add one now to ensure someone gets notified when there is a problem.</p>
            @endif
        </div>

        <div class="text-right">
            <eye-btn-link link="{{ route('eyewitness.recipients.create') }}" color="bg-brand" icon='@eyewitness_svg('bold-add', 'svgcolor-white h-4 w-4')'>New recipient</eye-btn-link>
        </div>
    </div>
</div>
