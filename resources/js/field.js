Nova.booting((Vue, router, store) => {
    Vue.component('index-qr-code-reader', require('./components/IndexField'))
    Vue.component('detail-qr-code-reader', require('./components/DetailField'))
    Vue.component('form-qr-code-reader', require('./components/FormField'))
})
