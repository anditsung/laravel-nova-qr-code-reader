<template>
    <DefaultField :field="field" :errors="errors">
        <template #field>
            <div class="flex items-top">
                <div v-if="! field.canInput">
                    <QRCodeVue3
                        :value="field.value"
                        :dots-options="dotsOptions"
                        :corners-square-options="cornersSquareOptions"
                        :corners-dot-options="cornersDotOptions"
                        :height="field.qrSizeForm"
                        :width="field.qrSizeForm"
                    />
                    <span class="ml-1" v-if="field.displayValue">{{ field.value }}</span>
                </div>
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
                <DefaultButton
                    class="ml-3"
                    @click.prevent="showModal = true"
                >
                    {{ __('Scan') }}
                </DefaultButton>
            </div>
            <camera-capture-modal
                :show="showModal"
                :canSubmit="field.canSubmit"
                :displayWidth="field.displayWidth"
                @close="showModal = false"
                @decoded="scanData"
            />
        </template>
    </DefaultField>
</template>

<script>
import QRCodeVue3 from "qrcode-vue3"
import CameraCaptureModal from "./CameraCaptureModal"
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    components: { QRCodeVue3, CameraCaptureModal },

    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            showModal: false,

            dotsOptions: {
                type: 'square',
            },
            cornersSquareOptions: {
                type: 'square'
            },
            cornersDotOptions: {
                type: 'square'
            }
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
