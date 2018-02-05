<div class="px-2 py-1" v-cloak>
    <eye-side-menu-btn name="#recipients">
        @eyewitness_svg('multiple-11', '', '')<br/>
        <span class="uppercase text-xxs font-semibold">Recipients</span>
    </eye-side-menu-btn>
</div>

<div class="px-2 py-1" v-cloak>
    <eye-side-menu-btn name="#severity">
        @eyewitness_svg('preferences-rotate', '', '')<br/>
        <span class="uppercase text-xxs font-semibold">Severity</span>
    </eye-side-menu-btn>
</div>

<div class="px-2 py-1" v-cloak>
    <eye-side-menu-btn name="{{ route('eyewitness.dashboard') }}#scheduler">
        @eyewitness_svg('double-left', '', '')<br/>
        <span class="uppercase text-xxs font-semibold">Back</span>
    </eye-side-menu-btn>
</div>
