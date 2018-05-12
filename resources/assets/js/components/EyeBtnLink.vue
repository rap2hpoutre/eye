<template>
    <a :href="btnLink" class="text-white font-bold pr-4 py-2 rounded inline-flex items-center no-underline shadow-md cursor-pointer" :class="buttonStatus" @click="pressButton" :disabled="disabledStatus">
        <span v-show="!loadingStatus" class="pl-3 mr-2 flex items-center" v-html="icon"></span>
        <transition enter-active-class="animated zoomIn" leave-active-class="animated zoomOut">
            <span v-show="loadingStatus" class="pl-2 mr-1 flex items-center">
                <svg class="h-4 w-6 eye-animate svgcolor-white" xmlns="http://www.w3.org/2000/svg" x="0" y="0" viewBox="0 0 393.7 236.7"><g class="nc-icon-wrapper" fill="#ffffff"><path fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M263.2 122c-1.6 37.2-33.1 66.1-70.3 64.5-37.2-1.6-66.1-33.1-64.5-70.3 1.6-37.2 33.1-66.1 70.3-64.5 37.2 1.6 66.1 33.1 64.5 70.3zm-12.8-.5c1.3-30.2-22.1-55.7-52.3-57-30.2-1.3-55.7 22.1-57 52.3-1.3 30.2 22.1 55.7 52.3 57 30.2 1.2 55.7-22.2 57-52.3z"></path><path fill="#ffffff" d="M231.9 118.6c-2.8 1.5-6.2 1.8-9.3.9-6.4-2-9.9-8.8-7.9-15.2 1.3-4.1 4.7-7.3 9-8.2l2.3-.5-1.5-1.7c-4.6-5.2-10.5-9-17.1-11.1-3.7-1.2-7.6-1.8-11.5-1.8-16.7 0-31.3 10.7-36.4 26.7-3.1 9.7-2.2 20 2.6 29.1 4.7 9 12.6 15.7 22.4 18.7 3.7 1.2 7.6 1.8 11.5 1.8 16.7 0 31.3-10.7 36.4-26.7 1.1-3.5 1.7-7.1 1.8-10.7v-2.3l-2.3 1z"></path><g class="eye-animate-right"><path fill="#ffffff" d="M262.3 76.3l15.4-10.1c10 15.2 15.9 33.4 16 52.9.1 18.7-5.2 36.2-14.3 51.1l-15.7-9.6c7.4-12 11.7-26.2 11.6-41.3-.2-15.9-4.9-30.7-13-43z"></path><path fill="#ffffff" d="M285.5 61l20.9-13.8c13.6 20.6 21.5 45.2 21.6 71.7.1 25.4-7 49.1-19.4 69.2L287.3 175c10-16.3 15.8-35.5 15.7-56-.1-21.4-6.5-41.3-17.5-58z"></path><path fill="#ffffff" d="M313.8 42.4c14.5 22 23 48.3 23.1 76.6.1 27.1-7.5 52.4-20.7 73.9l28.1 17.3c16.4-26.5 25.8-57.8 25.6-91.3-.1-35-10.6-67.4-28.5-94.6l-27.6 18.1z"></path></g><g class="eye-animate-left"><path fill="#ffffff" d="M130.8 76.8l-15.5-10c-9.9 15.3-15.7 33.5-15.6 53 .1 18.7 5.5 36.2 14.7 51L130 161c-7.5-12-11.9-26.1-11.9-41.3.1-15.8 4.7-30.5 12.7-42.9z"></path><path fill="#ffffff" d="M107.5 61.7l-21-13.6C73.1 68.8 65.3 93.5 65.4 120c.1 25.4 7.4 49 19.9 69l21.2-13.2c-10.2-16.2-16.1-35.4-16.1-55.9-.1-21.5 6.2-41.5 17.1-58.2z"></path><path fill="#ffffff" d="M79.1 43.2C64.7 65.3 56.4 91.7 56.5 120c.1 27.1 7.9 52.4 21.3 73.7l-28 17.5c-16.6-26.4-26.2-57.6-26.3-91.1-.1-35 10.1-67.5 27.9-94.8l27.7 17.9z"></path></g></g></svg>
            </span>
        </transition>
        <span>
            <slot></slot>
        </span>
    </a>
</template>

<script>
    export default {

        props: {
            icon: String,
            color: String,
            link: String,
            disabled: {
                default: false,
                type: Boolean
            }
        },

        data: function() {
            return {
                'isDisabled': this.disabled,
                'isLoading': false,
                'btnColor': this.color,
                'btnLink': this.link
            };
        },

        computed: {
            disabledStatus() {
                return this.isLoading || this.isDisabled;
            },

            loadingStatus() {
                return this.isLoading;
            },

            buttonStatus() {
                return [{
                    'opacity-50': this.disabledStatus,
                    'cursor-not-allowed': this.disabledStatus,
                    'hover:bg-brand-light' : !this.disabledStatus,
                    'hover:shadow-lg': !this.disabledStatus,
                    'btn-pop': !this.disabledStatus,
                }, this.btnColor];
            },
        },

        methods: {
            pressButton() {
                if (! this.disabledStatus) {
                    this.isLoading = true;
                }
            },
        }
    }
</script>
