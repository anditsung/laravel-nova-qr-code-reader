<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div class="flex">
                <vue-q-r-code-component v-if="!canInput" v-show="value" :text="value" :size="qrSizeForm"></vue-q-r-code-component>
                <div v-else>
                    <input
                        :id="field.name"
                        type="text"
                        class="w-full form-control form-input form-input-bordered"
                        :class="errorClasses"
                        :placeholder="field.name"
                        v-model="value"
                        :disabled="isReadonly"
                    />
                </div>
                <button class="btn btn-default btn-primary ml-3" @click.prevent="showModal = true">{{ __('Scan') }}</button>
            </div>
            <camera-capture-modal :displayWidth="displayWidth" :showSubmit="showSubmit" v-if="showModal" @close="showModal = false" @decoded="scanData"></camera-capture-modal>
        </template>
    </default-field>
</template>

<script>
    import CameraCaptureModal from "./CameraCaptureModal"
    import VueQRCodeComponent from 'vue-qrcode-component'
    import { FormField, HandlesValidationErrors } from 'laravel-nova'

    export default {
        components: { CameraCaptureModal, VueQRCodeComponent },

        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        data() {
            return {
                showModal: false,
                showSubmit: this.field.canSubmit,
                canInput: this.field.canInput,
                qrSizeForm: this.field.qrSizeForm,
                displayWidth: this.field.displayWidth,
            }
        },

        methods: {
            scanData(decodedString) {
                this.showModal = false
                this.value = decodedString
            },

            /*
             * Set the initial, internal value for the field.
             */
            setInitialValue() {
                this.value = this.field.value || ''
            },

            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                formData.append(this.field.attribute, this.value || '')
            },

            /**
             * Update the field's internal value.
             */
            handleChange(value) {
                this.value = value
            },
        },
    }
</script>
