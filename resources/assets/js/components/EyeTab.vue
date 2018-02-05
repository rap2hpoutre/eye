<template>
    <div v-show="isActive">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: [
            'name'
        ],

        data: function() {
            return {
                'tabName': this.name,
            }
        },
        computed: {
            isActive() {
                return window.bus.activeTab === this.tabName;
            }
        }
    }
</script>
