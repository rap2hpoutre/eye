<template>
    <div class="tab-details" v-show="isActive">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: {
            name: { required: true },
            image: { required: true },
            selected: { default: false }
        },

        data: function() {
            return {
                isActive: false
            }
        },

        mounted() {
            this.isActive = this.selected;
        }
    }
</script>
