import App from './components/ordershop.vue';

const app = new Vue({
    el: '#ordershop',
    components: {
        App
    },
    render: h => h(App)
});