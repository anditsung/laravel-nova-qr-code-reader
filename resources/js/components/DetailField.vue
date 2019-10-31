<template>
    <div class="flex border-b border-40">
        <div class="w-1/4 py-4">
            <slot>
                <h4 class="font-normal text-80">{{ label }}</h4>
            </slot>
        </div>
        <div class="w-3/4 py-4 break-words">
            <div class='flex' v-if="field.viewable && field.value">
                <router-link
                    v-if="field.viewable && field.value"
                    :to="{
                        name: 'detail',
                        params: {
                            resourceName: field.resourceName,
                            resourceId: field.belongsToId,
                        },
                    }"
                    class="no-underline font-bold dim text-primary"
                >
                    <vue-q-r-code-component v-if="fieldValue" :text="fieldValue" :size="qrSizeDetail"></vue-q-r-code-component>
                </router-link>
                <div class="ml-3" v-show="displayValue">
                    {{ fieldValue }}
                </div>
            </div>
            <div v-else-if="field.value" class="flex">
                <div>
                    <vue-q-r-code-component v-if="fieldValue" :text="fieldValue" :size="qrSizeDetail"></vue-q-r-code-component>
                </div>
                <div class="ml-3" v-show="displayValue">
                    {{ fieldValue }}
                </div>
            </div>
            <div v-else>&mdash;</div>
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
                qrSizeDetail: this.field.qrSizeDetail,
                displayValue: this.field.displayValue,
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
