<template>
    <div class="flex justify-end w-full">
        <transition enter-active-class="animated fadeInDown" leave-active-class="animated fadeOutUp">
            <div class="mt-8 mr-2 ml-2 md:mr-8 px-4 py-3 text-white z-40 inline-block fixed notification-transition cursor-pointer flex justify-end rounded text-center text-sm font-bold shadow max-w-sm" :class="backgroundColor" @click="closeNotification" v-show="showNotification">
                <div class="bg-white rounded shadow-lg -mt-6 w-8 h-8">

                    <div class="h-full flex justify-center items-center svgcolor-blue" v-show="this.notificationType === 'info'">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" xml:space="preserve" width="24" height="24"><g fill="#fb503b" class="nc-icon-wrapper"><path fill="#fb503b" d="M22.067,26.846l-0.408,1.67C18.83,29.63,18.003,30,16.186,30c-1.484,0-2.638-0.362-3.461-1.085 c-1.626-1.431-1.352-3.498-0.844-5.613l1.531-5.418c0.296-1.116,0.894-3.393,0.057-4.193c-0.839-0.8-2.79-0.295-3.926,0.13 l0.409-1.67c1.64-0.667,3.712-1.484,5.479-1.484c2.66,0,4.606,1.329,4.606,3.841c0,0.235-0.027,0.649-0.083,1.243 c-0.102,1.09-0.133,1.019-1.832,7.032c-0.189,0.661-0.484,2.066-0.484,2.746C17.639,28.146,20.683,27.499,22.067,26.846z"></path> <circle data-color="color-2" fill="#555555" cx="19" cy="5" r="3"></circle></g></svg>
                    </div>

                    <div class="h-full flex justify-center items-center svgcolor-orange" v-show="this.notificationType === 'warning'">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" xml:space="preserve" width="24" height="24"><g fill="#fb503b" class="nc-icon-wrapper"><polygon fill="#fb503b" points="18,21 14,21 13,2 19,2 "></polygon> <circle data-color="color-2" fill="#555555" cx="16" cy="27" r="3"></circle></g></svg>
                    </div>

                    <div class="h-full flex justify-center items-center svgcolor-green" v-show="this.notificationType === 'success'">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" xml:space="preserve" width="24" height="24"><g fill="#fb503b" class="nc-icon-wrapper"><path fill="#fb503b" d="M30.7,8.3l-4-4c-0.4-0.4-1-0.4-1.4,0L12,17.6l-5.3-5.3c-0.4-0.4-1-0.4-1.4,0l-4,4c-0.4,0.4-0.4,1,0,1.4 l10,10c0.2,0.2,0.5,0.3,0.7,0.3s0.5-0.1,0.7-0.3l18-18C31.1,9.3,31.1,8.7,30.7,8.3z"></path></g></svg>
                    </div>

                    <div class="h-full flex justify-center items-center svgcolor-red" v-show="this.notificationType === 'error'">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" xml:space="preserve" width="24" height="24"><g fill="#fb503b" class="nc-icon-wrapper"><path fill="#fb503b" d="M28.7,7.3l-4-4c-0.4-0.4-1-0.4-1.4,0L16,10.6L8.7,3.3c-0.4-0.4-1-0.4-1.4,0l-4,4c-0.4,0.4-0.4,1,0,1.4 l7.3,7.3l-7.3,7.3c-0.4,0.4-0.4,1,0,1.4l4,4c0.4,0.4,1,0.4,1.4,0l7.3-7.3l7.3,7.3c0.2,0.2,0.5,0.3,0.7,0.3s0.5-0.1,0.7-0.3l4-4 c0.4-0.4,0.4-1,0-1.4L21.4,16l7.3-7.3C29.1,8.3,29.1,7.7,28.7,7.3z"></path></g></svg>
                    </div>

                </div>
                <p class="ml-3">{{ notificationMessage }}</p>
                <div class="float-right svgcolor-white -mt-2 -mr-3 pl-2">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve" width="16" height="16"><g class="nc-icon-wrapper" fill="#444444"><line fill="none" stroke="#444444" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="11.5" y1="4.5" x2="4.5" y2="11.5"></line> <line fill="none" stroke="#444444" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="4.5" y1="4.5" x2="11.5" y2="11.5"></line></g></svg>
                </div>
            </div>
        </transition>
    </div>

</template>

<script>
    export default {
        data: function() {
            return {
                notificationType: null,
                notificationMessage: null,
                showNotification: false,
                dismissTimer: null
            };
        },
        computed: {
            backgroundColor() {
                if (this.notificationType === 'error') {
                    return 'bg-red';
                }
                if (this.notificationType === 'success') {
                    return 'bg-green';
                }
                if (this.notificationType === 'warning') {
                    return 'bg-orange';
                }
                return 'bg-blue';
            },
        },
        mounted () {
            window.bus.$on('showNotification', ($type, $message) => {
                this.createNotification($type, $message);
            })
        },
        methods: {
            closeNotification() {
                this.showNotification = false;
                this.dismissTimer = null;
            },
            createNotification(type, message) {
                this.notificationType = type;
                this.notificationMessage = message;
                this.showNotification = true;
                this.dismissTimer = setTimeout(function () {
                    this.closeNotification()
                }.bind(this), 5000)
            }
        }
    }
</script>
