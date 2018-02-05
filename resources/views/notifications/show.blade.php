@extends('eyewitness::layout')

@section('content')

    <div class="flex w-full max-w-1200 mx-auto pt-6 mt-8 md:px-4 lg:px-8">
        <div class="bg-white border-t border-b sm:rounded sm:border shadow-inner w-full">
            <div class="flex mb-8 border-b border-grey-light pb-4">
                <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
                    <div class="h-full flex justify-center items-center">
                        @eyewitness_svg('notification-69', 'svgcolor-white')
                    </div>
                </div>
                <h1 class="font-hairline ml-4 mt-2 text-2xl">View notification</h1>
            </div>
            <div class="px-6 pb-4">
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/2 md:w-1/4 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Notification</p>
                        </div>
                        <div class="w-1/2 md:w-3/4">
                            <p class="text-grey-darker ml-4">{{ $notification->title }}</p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/2 md:w-1/4 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Created</p>
                        </div>
                        <div class="w-1/2 md:w-3/4">
                            <p class="text-grey-darker ml-4">{{ $notification->created_at->format('Y-m-d H:i:s') }} <span class="text-grey italic">({{ $notification->created_at->diffForHumans() }})</span></p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/2 md:w-1/4 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Status</p>
                        </div>
                        <div class="w-1/2 md:w-3/4 inline-flex">
                            <p class="ml-4 max-w-full font-mono rounded text-xs text-white p-1 {{ $notification->acknowledged ? 'bg-green' : 'bg-red' }}">{{ $notification->acknowledged ? 'Acknowledged' : 'Outstanding' }}</p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/2 md:w-1/4 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Type</p>
                        </div>
                        <div class="w-1/2 md:w-3/4 inline-flex">
                            <p class="ml-4 max-w-full font-mono rounded text-xs text-white p-1 {{ $notification->isError ? 'bg-red' : 'bg-green' }}">{{ $notification->isError ? 'Error' : 'Notification' }}</p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/2 md:w-1/4 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Severity</p>
                        </div>
                        <div class="w-1/2 md:w-3/4 inline-flex">
                            <p class="ml-4 max-w-full font-mono rounded text-xs text-white p-1 bg-grey">{{ ucfirst($notification->severity) }}</p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/2 md:w-1/4 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Description</p>
                        </div>
                        <div class="w-1/2 md:w-3/4">
                            <p class="text-grey-darker ml-4">{{ $notification->description }}</p>
                        </div>
                    </div>
                </div>
                @if (count($notification->meta))
                    <div class="pl-1 pb-3 pt-3">
                        <div class="flex">
                            <div class="w-1/2 md:w-1/4 text-right">
                                <p class="block tracking-wide text-grey text-right mr-1">Details</p>
                            </div>
                            <div class="w-1/2 md:w-3/4 text-grey-darker">
                                @foreach($notification->meta as $name => $meta)
                                    <p class="mb-2"><span class="ml-4 font-bold">{{ $name }}:</span> <span>{{ $meta }}</span></p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <div class="flex mt-8 border-t pt-4">
                    <div class="flex-1">
                        <eye-btn-link link="{{ route('eyewitness.dashboard') }}#notifications" color="bg-brand" icon='@eyewitness_svg('double-left', 'svgcolor-white h-4 w-4')'>Back</eye-btn-link>
                    </div>
                    @if (! $notification->acknowledged)
                        <div class="flex-1 text-right">
                            <form v-on:submit.capture="formSubmit" class="" method="POST" action="{{ route('eyewitness.notifications.update', $notification->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="put">

                                <eye-btn color="bg-brand" type="submit" icon='@eyewitness_svg('check-square-11', 'svgcolor-white h-4 w-4')'>Acknowledge notification</eye-btn>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
