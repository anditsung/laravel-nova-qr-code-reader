<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <Link
                v-if="field.viewable && field.value"
                :href="$url(`/resources/${field.resourceName}/${field.belongsToId}`)"
                class="link-default"
            >
                <div>
                    <QRCodeVue3 :value="field.value"
                                :dots-options="dotsOptions"
                                :corners-square-options="cornersSquareOptions"
                                :corners-dot-options="cornersDotOptions"
                                :height="field.qrSizeDetail"
                                :width="field.qrSizeDetail"
                    />
                    <span class="ml-2" v-if="field.displayValue">
                        {{ fieldValue }}
                    </span>
                </div>
            </Link>
            <p v-else-if="field.value">
                <QRCodeVue3 :value="field.value"
                            :dots-options="dotsOptions"
                            :corners-square-options="cornersSquareOptions"
                            :corners-dot-options="cornersDotOptions"
                            :height="field.qrSizeDetail"
                            :width="field.qrSizeDetail"
                />
                <span class="ml-2" v-if="field.displayValue">
                    {{ fieldValue }}
                </span>
            </p>
            <p v-else>&mdash;</p>
        </template>
    </PanelItem>
</template>

<script>
import QRCodeVue3 from "qrcode-vue3"

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    components: {
        QRCodeVue3,
    },

    data() {
        return {
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
