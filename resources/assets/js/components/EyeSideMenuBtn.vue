<template>
    <a :href="name" class="no-underline text-grey-darker" @click="setActiveTab">
        <div class="text-center btn-side-menu bg-circuit bg-md btn-pop p-3 w-full rounded" :class="isActive">
            <slot></slot>
        </div>
    </a>
</template>

<script>
    export default {
        props: [
            'name'
        ],
        data: function() {
            return {
                'tabName': this.name
            }
        },
        computed: {
            isActive() {
                return {
                    'btn-side-menu-active': (window.bus.activeTab === this.tabName),
                    'svgcolor-white': (window.bus.activeTab === this.tabName),
                };
            }
        },

        methods: {
            setActiveTab() {
                window.bus.setActiveTab(this.name);
            },
        }
    }
</script>
