<template>
    <div class="container">
        <div class="container-fluid">
            <h2>My Orders</h2>
            <table class="table table-hover table-dark">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Description</th>
                        <th>Cost</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="P in myOrder">
                        <td>{{P.order_number}}</td>
                        <td>{{P.product_description}}</td>
                        <td>{{P.product_cost}}</td>
                        <td>{{P.payment_status}}</td>
                        <td v-if="P.payment_status=='CREATED'"><button class="btn btn-primary btn-sm" @click="openUrl(P.payment_processurl)">Buy</button></td>
                        <td v-if="P.payment_status=='REJECTED'">X</td>
                        <td v-if="P.payment_status=='APPROVED'">S</td>
                    </tr>
                </tbody>
            </table>           
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                myOrder:[{}],
            }
        },
        methods:{
            myOrders(){
                 window.axios.post('myOrderShop').then(({ data }) => {
                if(!data.error){
                        this.myOrder = data.Mensaje;
                    }else{
                        this.ErrorInterno();
                    }
                });
            },
            internalError(){
                alert("Internal Error");
            }
        },created(){
            this.myOrders();
        }
    }
</script>