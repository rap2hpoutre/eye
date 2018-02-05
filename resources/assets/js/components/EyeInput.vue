<template>
    <div class="z-10 ml-4 mb-2 w-full input-effect float-left relative">
        <input v-model="inputValue" class="appearance-none leading-normal eye-input py-1 border-b bg-transparent w-full" :class="hasContent" :id="inputId" :type="inputType" :name="inputName" placeholder="" required>
        <label class="absolute pin-l w-full text-grey" v-html="inputLabel"></label>
        <span class="absolute focus-border bg-brand pin-b pin-l"></span>
    </div>
</template>

<script>
    export default {

        props: [
            'type',
            'name',
            'label',
            'value',
            'id'
        ],

        data: function() {
            return {
                'inputType': this.type,
                'inputName': this.name,
                'inputValue': this.value,
                'inputLabel': this.label,
                'inputId': this.id,
            };
        },

        computed: {
            hasContent() {
                return {
                    'has-content': (this.inputValue != ''),
                };
            },
        },
    }
</script>
