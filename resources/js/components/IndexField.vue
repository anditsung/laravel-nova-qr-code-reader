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
                    }"<router-link
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
                {{ field.value }}
            </router-link>
            <p v-else-if="field.value">{{ field.value }}</p>
            <p v-else>&mdash;</p>
                class="no-underline dim text-primary font-bold"
            >
                <vue-q-r-code-component v-if="field.value" :text="field.value" :size="30"></vue-q-r-code-component>
            </router-link>
        </span>
        <span v-else-if="field.value">
            <vue-q-r-code-component v-if="field.value" :text="field.value" :size="30"></vue-q-r-code-component>
        </span>
        <span v-else>-</span>
    </span>
</template>

<script>
    import VueQRCodeComponent from 'vue-qrcode-component'

    export default {
        components: { VueQRCodeComponent },

        props: ['resourceName', 'field'],
    }
</script>
