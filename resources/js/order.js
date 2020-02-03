import App from './components/order.vue';

const app = new Vue({
    el: '#order',
    components: {
        App
    },
    render: h => h(App)
});