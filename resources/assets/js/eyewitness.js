
require('./bootstrap');
require('./chart');

window.Vue = require('vue');
window.bus = new Vue({
    data: {
        activeTab: window.location.hash
    },
    methods: {
        setDefaultTab(name) {
            if (this.activeTab.length < 1) {
                this.activeTab = name;
            }
        },
        setActiveTab(name) {
            this.activeTab = name;
            this.$emit('tabChanged');
            this.$nextTick(function () {
                window.dispatchEvent(new Event('resize'));
            });
        }
    }
});

Vue.component('eye-menu', require('./components/EyeMenu.vue'));
Vue.component('eye-modal', require('./components/EyeModal.vue'));
Vue.component('eye-mobile-menu', require('./components/EyeMobileMenu.vue'));
Vue.component('eye-btn', require('./components/EyeBtn.vue'));
Vue.component('eye-btn-link', require('./components/EyeBtnLink.vue'));
Vue.component('eye-side-menu-btn', require('./components/EyeSideMenuBtn.vue'));
Vue.component('eye-input', require('./components/EyeInput.vue'));
Vue.component('eye-tab', require('./components/EyeTab.vue'));
Vue.component('eye-notification', require('./components/EyeNotification.vue'));
Vue.component('eye-recipients', require('./components/EyeRecipients.vue'));
Vue.component('eye-recipient', require('./components/EyeRecipient.vue'));

var app = new Vue({
    el: '#app',
    data: {
        formAction: '',
        formMethod: '',
        modal: false
    },
    methods: {
        formSubmit() {
            window.bus.$emit('formSubmitting');
        },
        showNotification(type, message) {
            window.bus.$emit('showNotification', type, message);
        },
        submitForm(e) {
            this.formAction = e.currentTarget.getAttribute('data-action');
            this.formMethod = e.currentTarget.getAttribute('data-method');
            this.$nextTick(() => {
                document.getElementById('genericForm').submit();
            });
        },
    }
});
