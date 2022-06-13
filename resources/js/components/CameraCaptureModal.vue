<template>
    <Modal
        :show="show"
        @close-via-escape="handleClose"
    >
        <form
            ref="theForm"
            autocomplete="off"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden"
        >
            <div>
                <ModalHeader v-text="__('Scan QR Code')" />
                <ModalContent>
                    <div>
                        <input type="text"
                               class="w-full form-control form-input form-input-bordered"
                               v-model="code"
                               readonly
                        />
                        <HelpText class="mt-2 help-text-error" v-if="hasError">
                            {{ __(this.errorMessage) }}
                        </HelpText>
                    </div>

                    <div>
                        <HelpText class="mt-2 help-text-error" v-if="cameraError">
                            {{ __(this.cameraErrorMessage) }}
                        </HelpText>
                        <QrStream
                            v-else
                            class="pt-4"
                            v-bind:style="{width: displayWidth}"
                            @init="onInit"
                            @decode="onDecode" />
                    </div>

                </ModalContent>
                <ModalFooter>
                    <div class="ml-auto">
                        <LinkButton
                            dusk="cancel-upload-delete-button"
                            type="button"
                            @click.prevent="handleClose"
                            class="mr-3"
                        >
                            {{ __('Cancel') }}
                        </LinkButton>

                        <LoadingButton
                            v-if="canSubmit"
                            @click.prevent="handleConfirm"
                            :disabled="clicked"
                            :processing="clicked"
                            component="DefaultButton"
                        >
                            {{ __('Submit') }}
                        </LoadingButton>
                    </div>
                </ModalFooter>
            </div>
        </form>
    </Modal>
</template>

<script>
import { QrStream } from "vue3-qr-reader"

export default {
    emits: ['submit', 'close'],

    components: {
        QrStream
    },

    props: {
        show: {
            type: Boolean,
            default: false
        },
        canSubmit: {
            type: Boolean,
            default: false
        },
        displayWidth: {
            type: String,
            default: 'auto'
        }
    },

    data() {
        return {
            clicked: false,

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
            if(! this.canSubmit) {
                this.$emit('decoded', this.code)
            }

        },

        /**
         * Execute the selected action.
         */
        handleConfirm() {
            if(! this.code) {
                this.hasError = true
                this.errorMessage = "Please scan code before submitting!"
                return
            }
            this.$emit('decoded', this.code)
            this.reset()
        },

        /**
         * Close the modal.
         */
        handleClose() {
            this.reset()
            this.$emit('close')
        },

        reset() {
            this.code = ""
            this.errorMessage = ""
        }
    },
}
</script>
