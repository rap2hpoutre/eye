<template>
    <div>
        <div v-if="showModal" @click.self="closeModal" class="animated fadeIn fixed z-50 pin overflow-auto bg-smoke-dark flex">
            <div class="animated fadeInDown fixed shadow-inner max-w-md relative pin-b pin-x align-top m-auto justify-center p-4 bg-white md:rounded w-full md:h-auto md:shadow flex flex-col">
                <h2 class="text-xl text-left font-hairline text-grey mb-4 ml-2 align-top">{{ title }}</h2>
                <p class="text-xl leading-normal mb-4">
                    <slot name="body"></slot>
                </p>
                <span @click="closeModal" class="absolute pin-t pin-r mt-4 mr-4">
                    <svg class="h-6 w-6 cursor-pointer" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" xml:space="preserve" width="32" height="32"><g class="nc-icon-wrapper" fill="#fb503b"><path fill="#fb503b" d="M28.7,7.3l-4-4c-0.4-0.4-1-0.4-1.4,0L16,10.6L8.7,3.3c-0.4-0.4-1-0.4-1.4,0l-4,4c-0.4,0.4-0.4,1,0,1.4 l7.3,7.3l-7.3,7.3c-0.4,0.4-0.4,1,0,1.4l4,4c0.4,0.4,1,0.4,1.4,0l7.3-7.3l7.3,7.3c0.2,0.2,0.5,0.3,0.7,0.3s0.5-0.1,0.7-0.3l4-4 c0.4-0.4,0.4-1,0-1.4L21.4,16l7.3-7.3C29.1,8.3,29.1,7.7,28.7,7.3z"></path></g></svg>
                </span>
            </div>
        </div>
        <button type="btn" class="bg-brand text-white font-bold py-1 px-2 rounded inline-flex shadow-md items-center btn-pop hover:shadow-lg hover-bg-brand-light" @click="openModal">
            <slot name="button"></slot>
        </button>
    </div>
</template>

<script>
    export default {

        props: [
            'title',

        ],
        data: function() {
            return {
                'showModal': false,
            };
        },

        methods: {
            openModal() {
                this.showModal = true;
            },
            closeModal() {
                this.showModal = false;
            }
        }
    }
</script>
