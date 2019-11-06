<template>
    <modal
        tabindex="-1"
        role="dialog"
        @modal-close="handleClose"
    >
        <form
            autocomplete="off"
            @keydown="handleKeydown"
            @submit.prevent.stop="handleConfirm"
            class="bg-white rounded-lg shadow-lg overflow-hidden"
        >
            <div>
                <heading :level="2" class="border-b border-40 py-8 px-8 text-center">{{ __('Scan QR Code') }}</heading>
                <div class="border-b border-40" v-if="showSubmit">
                    <div class="py-6 px-8 w-full">
                        <input
                            type="text"
                            class="w-full form-control form-input form-input-bordered text-center"
                            readonly="true"
                            v-model="code"
                        />
                        <div class="help-text error-text mt-2 text-danger" v-if="hasError">
                            {{ __(this.errorMessage) }}
                        </div>
                    </div>
                </div>
                <div class="border-b border-40">
                    <div class="py-6 px-8 w-full">
                        <span v-if="cameraError">
                            <div class="help-text error-text mt-2 text-danger" v-if="cameraError">
                                {{ __(this.cameraErrorMessage) }}
                            </div>
                        </span>
                        <qrcode-stream v-bind:style="{width: displayWidth}" v-else @init="onInit" @decode="onDecode"></qrcode-stream>
                    </div>
                </div>
            </div>

            <div class="bg-30 px-6 py-3 flex">
                <div class="flex items-center ml-auto">
                    <button
                        dusk="cancel-button"
                        type="button"
                        @click.prevent="handleClose"
                        class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6"
                    >
                        {{ __('Cancel') }}
                    </button>

                    <button
                        v-show="showSubmit"
                        ref="captureButton"
                        dusk="capture-button"
                        type="submit"
                        class="btn btn-default btn-primary">
                        <loader v-if="working" width="30"></loader>
                        <span v-else>{{ __('Submit') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </modal>
</template>

<script>
    import { QrcodeStream } from 'vue-qrcode-reader'

    export default {
        components: {
            QrcodeStream
        },

        props: [
            'showSubmit',
            'displayWidth'
        ],

        data() {
            return {
                code: "",
                showInput: false,
                hasError: false,
                errorMessage: '',
                cameraError: false,
                cameraErrorMessage: ''
            }
        },

        methods: {
            async onInit(promise) {
                try {
                    await promise
                } catch(error) {
                    this.cameraError = true
                    switch (error.name) {
                        case "NotAllowedError":
                            this.cameraErrorMessage = "User denied camera access permissions"
                            break;
                        case "NotFoundError":
                            this.cameraErrorMessage = "No suitable camera device installed"
                            break;
                        case "NotSupportedError":
                            this.cameraErrorMessage = "Page is not served over HTTPS (or localhost)"
                            break;
                        case "NotReadableError":
                            this.cameraErrorMessage = "Maybe camera is already in use"
                            break;
                        case "OverconstrainedError":
                            this.cameraErrorMessage = "Did you requested the front camera although there is none?"
                            break;
                        case "StreamApiNotSupportedError":
                            this.cameraErrorMessage = "Browser seems to be lacking features"
                            break;
                        default:
                            this.cameraErrorMessage = "Unknown Error"
                            break;
                    }
                }
            },

            onDecode(decodedString) {
                this.code = decodedString
                if(!this.showSubmit) {
                    this.$emit('decoded', this.code)
                }

            },

            /**
             * Stop propogation of input events unless it's for an escape or enter keypress
             */
            handleKeydown(e) {
                if (['Escape', 'Enter'].indexOf(e.key) !== -1) {
                    return
                }

                e.stopPropagation()
            },

            /**
             * Execute the selected action.
             */
            handleConfirm() {
                if(!this.code) {
                    this.hasError = true
                    this.errorMessage = "Please scan code before submitting!"
                    return
                }
                this.$emit('decoded', this.code)
            },

            /**
             * Close the modal.
             */
            handleClose() {
                this.$emit('close')
            },
        },
    }
</script>
