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
                <vue-q-r-code-component v-if="field.value" :text="field.value" :size="qrSizeIndex"></vue-q-r-code-component>
            </router-link>
        </span>
        <span v-else-if="field.value">
            <vue-q-r-code-component v-if="field.value" :text="field.value" :size="qrSizeIndex"></vue-q-r-code-component>
        </span>
        <span v-else>&mdash;</span>
    </span>
</template>

<script>
    import VueQRCodeComponent from 'vue-qrcode-component'

    export default {
        components: { VueQRCodeComponent },

        props: ['resourceName', 'field'],

        data() {
            return {
                qrSizeIndex: this.field.qrSizeIndex
            }
        },
    }
</script>
