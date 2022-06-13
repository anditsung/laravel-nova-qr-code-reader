<template>
    <span>
        <span v-if="field.viewable && field.value">
            <router-link
                :to="{
                    name: 'detail',
                    params: {
                        resourceName: field.resourceName,
                        resourceId: field.belongsToId,
                    },
                }"
                class="no-underline dim text-primary font-bold"
            >
                <QRCodeVue3 :value="field.value"
                            :dots-options="dotsOptions"
                            :corners-square-options="cornersSquareOptions"
                            :corners-dot-options="cornersDotOptions"
                            :height="field.qrSizeIndex"
                            :width="field.qrSizeIndex"
                />
            </router-link>
        </span>
        <span v-else-if="field.value">
            <QRCodeVue3 :value="field.value"
                        :dots-options="dotsOptions"
                        :corners-square-options="cornersSquareOptions"
                        :corners-dot-options="cornersDotOptions"
                        :height="field.qrSizeIndex"
                        :width="field.qrSizeIndex"
            />
        </span>
        <span v-else>&mdash;</span>
    </span>
</template>

<script>
import QRCodeVue3 from "qrcode-vue3"

export default {

    props: ['resourceName', 'field'],

    components: {
        QRCodeVue3,
    },

    data: () => ({
        dotsOptions: {
            type: 'square',
        },
        cornersSquareOptions: {
            type: 'square'
        },
        cornersDotOptions: {
            type: 'square'
        }
    }),
}
</script>
