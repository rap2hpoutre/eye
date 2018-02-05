<div class="px-2 py-1" v-cloak>
    <eye-side-menu-btn name="#overview">
        @eyewitness_svg('analytics-89', '', '')<br/>
        <span class="uppercase text-xxs font-semibold">Overview</span>
    </eye-side-menu-btn>
</div>

<div class="px-2 py-1" v-cloak>
    <eye-side-menu-btn name="#history">
        @eyewitness_svg('timeline', '', '')<br/>
        <span class="uppercase text-xxs font-semibold">History</span>
    </eye-side-menu-btn>
</div>

<div class="px-2 py-1" v-cloak>
    <eye-side-menu-btn name="#settings">
        @eyewitness_svg('ui-04', '', '')<br/>
        <span class="uppercase text-xxs font-semibold">Settings</span>
    </eye-side-menu-btn>
</div>

<div class="px-2 py-1" v-cloak>
    <eye-side-menu-btn name="{{ route('eyewitness.dashboard') }}#queue">
        @eyewitness_svg('double-left', '', '')<br/>
        <span class="uppercase text-xxs font-semibold">Back</span>
    </eye-side-menu-btn>
</div>
