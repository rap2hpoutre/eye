<template>
    <div class="relative">
        <div id="demo" role="button" class="inline-block select-none bg-transparent hover:bg-brand-light text-white uppercase text-xs font-semibold hover:text-white py-2 px-2 rounded svg-hover" @click="onClick" v-click-outside="onClickOutside">
            <div class="appearance-none flex items-center inline-block text-white font-medium">
                <div v-if="title.length">
                    <span class="mr-2">{{ title }}</span>
                </div>

                <svg v-show="!open" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" xml:space="preserve" width="18" height="18" :class="color"><g class="nc-icon-wrapper" fill="#ffffff"><line data-color="color-2" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="2" y1="16" x2="30" y2="16" stroke-linejoin="miter"></line> <line fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="2" y1="7" x2="30" y2="7" stroke-linejoin="miter"></line> <line fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="2" y1="25" x2="30" y2="25" stroke-linejoin="miter"></line></g></svg>

                <svg v-show="open" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" xml:space="preserve" width="18" height="18" :class="color"><g class="nc-icon-wrapper" fill="#ffffff"><line fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="27" y1="5" x2="5" y2="27" stroke-linejoin="miter"></line> <line fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="27" y1="27" x2="5" y2="5" stroke-linejoin="miter"></line></g></svg>
            </div>
        </div>
        <transition enter-active-class="animated zoomIn" leave-active-class="animated zoomOut">
            <div class="absolute pin-r mt-1 z-50" v-show="open">
                <slot name="dropdown"></slot>
            </div>
        </transition>
    </div>
</template>

<script>
    import vClickOutside from 'v-click-outside'
    export default {
        props: {
            title: { default: 'menu' },
            color: { default: 'svgcolor-white' }
        },
        directives: {
              clickOutside: vClickOutside.directive
        },
        data: function() {
            return {
                open: false
            };
        },
        methods: {
            onClick() {
                this.open = !this.open;
            },
            onClickOutside (event) {
                this.open = false;
            }
        }
    }
</script>
