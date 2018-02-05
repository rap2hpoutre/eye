<div class="flex flex-wrap">
    <div class="w-1/2 px-1 py-1" v-cloak>
        <eye-side-menu-btn name="#overview">
            @eyewitness_svg('analytics-89', '', '')<br/>
            <span class="uppercase text-xxs font-semibold">Overview</span>
        </eye-side-menu-btn>
    </div>
    @if (config('eyewitness.monitor_scheduler'))
        <div class="w-1/2 px-1 py-1" v-cloak>
            <eye-side-menu-btn name="#scheduler">
                @eyewitness_svg('calendar-grid-58', '', '')<br/>
                <span class="uppercase text-xxs font-semibold">Scheduler</span>
            </eye-side-menu-btn>
        </div>
    @endif
    @if (config('eyewitness.monitor_queue'))
        <div class="w-1/2 px-1 py-1" v-cloak>
            <eye-side-menu-btn name="#queue">
                @eyewitness_svg('design-system', '', '')<br/>
                <span class="uppercase text-xxs font-semibold">Queue</span>
            </eye-side-menu-btn>
        </div>
    @endif
    @if (config('eyewitness.display_helpers'))
        <div class="w-1/2 px-1 py-1" v-cloak>
            <eye-side-menu-btn name="#custom">
                @eyewitness_svg('selection', '', '')<br/>
                <span class="uppercase text-xxs font-semibold">Custom</span>
            </eye-side-menu-btn>
        </div>
    @endif
    @if (config('eyewitness.monitor_database'))
        <div class="w-1/2 px-1 py-1" v-cloak>
            <eye-side-menu-btn name="#database">
                @eyewitness_svg('database-2', '', '')<br/>
                <span class="uppercase text-xxs font-semibold">Database</span>
            </eye-side-menu-btn>
        </div>
    @endif
    @if (config('eyewitness.monitor_dns'))
        <div class="w-1/2 px-1 py-1" v-cloak>
            <eye-side-menu-btn name="#dns">
                @eyewitness_svg('world-pin', '', '')<br/>
                <span class="uppercase text-xxs font-semibold">DNS</span>
            </eye-side-menu-btn>
        </div>
    @endif
    @if (config('eyewitness.monitor_ssl'))
        <div class="w-1/2 px-1 py-1" v-cloak>
            <eye-side-menu-btn name="#ssl">
                @eyewitness_svg('l-security', '', '')<br/>
                <span class="uppercase text-xxs font-semibold">SSL</span>
            </eye-side-menu-btn>
        </div>
    @endif
    @foreach($eye->getCustomWitnesses() as $witness)
        <div class="w-1/2 px-1 py-1" v-cloak>
            <eye-side-menu-btn name="#{{ $witness->getSafeName() }}">
                {!! $witness->getIcon() !!}<br/>
                <span class="uppercase text-xxs font-semibold">{{ $witness->displayName }}</span>
            </eye-side-menu-btn>
        </div>
    @endforeach
    @if (config('eyewitness.monitor_composer'))
        <div class="w-1/2 px-1 py-1" v-cloak>
            <eye-side-menu-btn name="#composer">
                @eyewitness_svg('cctv', '', '')<br/>
                <span class="uppercase text-xxs font-semibold">Composer</span>
            </eye-side-menu-btn>
        </div>
    @endif
    <div class="w-1/2 px-1 py-1" v-cloak>
        <eye-side-menu-btn name="#notifications">
            @eyewitness_svg('notification-69', '', '')<br/>
            <span class="uppercase text-xxs font-semibold">Notifications</span>
        </eye-side-menu-btn>
    </div>
</div>
