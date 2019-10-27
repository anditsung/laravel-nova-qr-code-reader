<template>
    <div class="flex border-b border-40">
        <div class="w-1/4 py-4">
            <slot>
                <h4 class="font-normal text-80">{{ label }}</h4>
            </slot>
        </div>
        <div class="w-3/4 py-4 break-words">
            <vue-q-r-code-component v-if="fieldValue" :text="fieldValue" :size="qrSize"></vue-q-r-code-component>
        </div>
    </div>
</template>

<script>
    import VueQRCodeComponent from 'vue-qrcode-component'

    export default {

        components: { VueQRCodeComponent },

        props: ['resource', 'resourceName', 'resourceId', 'field'],

        data() {
            return {
                qrSize: this.field.qrSize
            }
        },

        computed: {
            label() {
                return this.fieldName || this.field.name
            },

            fieldValue() {
                if (
                    this.field.value === '' ||
                    this.field.value === null ||
                    this.field.value === undefined
                ) {
                    return false
                }

                return String(this.field.value)
            },
        },
    }
</script>
