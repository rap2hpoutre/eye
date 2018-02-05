<div class="bg-white border-t border-b sm:rounded sm:border shadow-inner">
    <div class="flex mb-8 border-b border-grey-light pb-4">
        <div class="flex-none bg-brand bg-circuit bg-lg ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
            <div class="h-full flex justify-center items-center">
                @eyewitness_svg('preferences-rotate', 'svgcolor-white')
            </div>
        </div>
        <h1 class="font-hairline ml-4 mt-2 text-2xl">Notification severity</h1>
    </div>
    <div class="text-center px-6 pb-4">
        @eyewitness_tutorial('These are a list of notifications and their corresponding severity level. These levels are pre-configured with sensible defaults that should suit most applications. You can tweak them as required.')

        @if (count($severities))
            <form v-on:submit.capture="formSubmit" class="form" method="POST" action="{{ route('eyewitness.severity.update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="put">

                @foreach($severities->groupBy('namespace') as $id => $lists)
                    <div class="pb-8">
                        <p class="text-grey-darkest font-bold text-2xl pb-2 pt-4 text-center border-t">{{ $id }}</p>
                        @foreach($lists as $item)
                            <div class="pl-2 pb-2 pt-2">
                                <div class="flex md:items-center">
                                    <div class="w-1/2">
                                        <label class="block tracking-wide text-grey text-right mr-4" for="sev_{{ $item->id }}">
                                            {{ preg_replace('/(\w+)([A-Z])/U', '\\1 \\2', $item->notification) }}:
                                        </label>
                                    </div>
                                    <div class="w-1/2">
                                        <div class="relative w-24">
                                            <select id="sev_{{ $item->id }}" name="notification[{{ $item->id }}]" class="appearance-none w-full bg-brand text-white py-1 px-2 pr-2 rounded">
                                                <option value="disabled" {{ $item->severity === 'disabled' ? 'selected="selected"' : '' }}>Disabled</option>
                                                <option value="low" {{ $item->severity === 'low' ? 'selected="selected"' : '' }}>Low</option>
                                                <option value="medium" {{ $item->severity === 'medium' ? 'selected="selected"' : '' }}>Medium</option>
                                                <option value="high" {{ $item->severity === 'high' ? 'selected="selected"' : '' }}>High</option>
                                            </select>
                                            <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-white">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <div class="pt-4">
                    <div class="text-right">
                        <eye-btn color="bg-brand" type="submit" icon='@eyewitness_svg('check-square-11', 'svgcolor-white h-4 w-4')'>Save changes</eye-btn>
                    </div>
                </div>
            </form>
        @else
            <div class="py-8">
                <div class="mb-4">
                    @eyewitness_svg('wifi-off')
                </div>
                <p class="text-2xl text-grey-darker font-medium mb-4">No severity settings found.</p>
                <p class="text-grey max-w-xs mx-auto mb-6">Did you clear the database? You should run the Eyewitness migrations to seed the table.</p>
            </div>
        @endif
    </div>
</div>
