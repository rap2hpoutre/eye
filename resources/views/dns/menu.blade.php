@foreach ($dns as $record)
    <div class="px-2 py-1" v-cloak>
        <eye-side-menu-btn name="#{{ $record['created_at']->timestamp }}">
            @eyewitness_svg('single-copy-04', '', '')<br/>
            <span class="uppercase text-xxs font-semibold">{{ $record['created_at']->format('Y-m-d H:i:s') }}</span>
        </eye-side-menu-btn>
    </div>
@endforeach

<div class="px-2 py-1" v-cloak>
    <eye-side-menu-btn name="{{ route('eyewitness.dashboard') }}#dns">
        @eyewitness_svg('double-left', '', '')<br/>
        <span class="uppercase text-xxs font-semibold">Back</span>
    </eye-side-menu-btn>
</div>
