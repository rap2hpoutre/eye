<template>
    <div>
        <div class="flex flex-wrap justify-center">
            <div v-for="tab in tabs" class="text-center m-1 p-3 cursor-pointer btn-pop hover:bg-grey-light" :class="{ 'bg-grey-light': tab.isActive }" @click="selectTab(tab)">
                <img :src="'data:image/x-icon;base64,'+tab.image" /><br/>
                <p class="uppercase text-xs text-grey-darker mt-1">{{ tab.name }}</p>
            </div>
        </div>
        <div class="tab-details">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                'tabs': null
            };
        },

        created() {
            this.tabs = this.$children;
        },

        methods: {
            selectTab(selectedTab) {
                this.tabs.forEach(tab => {
                    tab.isActive = (tab.name === selectedTab.name);
                })
            },
        }

    }
</script>
