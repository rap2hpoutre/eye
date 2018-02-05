<template>
    <div class="relative">
        <transition enter-active-class="animated fadeIn" leave-active-class="">
            <div v-show="open">
                <slot name="dropdown"></slot>
            </div>
        </transition>
        <div class="appearance-none flex items-center justify-center w-full inline-block font-medium ext-center px-6 py-4 cursor-pointer" @click="onClick">
            <span class="mr-2"><slot name="name"></slot></span>

            <svg v-show="open" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper" fill="#444444"><path fill="#444444" d="M22.412,16.809L12,9.236L1.588,16.809l-1.177-1.617l11-8c0.351-0.256,0.826-0.256,1.177,0l11,8 L22.412,16.809z"></path></g></svg>

            <svg v-show="!open" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper" fill="#444444"><path fill="#444444" d="M12,17c-0.207,0-0.413-0.063-0.588-0.191l-11-8l1.177-1.617L12,14.764l10.412-7.572l1.177,1.617l-11,8 C12.413,16.937,12.207,17,12,17z"></path></g></svg>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                open: false
            };
        },
        mounted () {
            window.bus.$on('tabChanged', this.closeMenu);
        },
        methods: {
            onClick() {
                this.open = !this.open;
            },
            closeMenu() {
                this.open = false;
            }
        }
    }
</script>
