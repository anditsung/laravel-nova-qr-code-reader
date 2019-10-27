<template>
    <span>
        <vue-q-r-code-component v-if="field.value" :text="field.value" :size="30"></vue-q-r-code-component>
    </span>
</template>

<script>
    import VueQRCodeComponent from 'vue-qrcode-component'

    export default {
        components: { VueQRCodeComponent },

        props: ['resourceName', 'field'],
    }
</script>
