<template>
    <div class="container">
        <div class="container-fluid">
            <h2>Audiovisual Products</h2>
            <table class="table table-hover table-dark">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Cost</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="P in Products">
                        <td>{{P.product_code}}</td>
                        <td>{{P.product_description}}</td>
                        <td>{{P.product_cost}}</td>
                        <td><button class="btn btn-primary btn-sm" @click="buyProduct(P.id,P.product_code,P.product_description,P.product_cost)">Order</button></td>
                    </tr>
                </tbody>
            </table>           
        </div>
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
                        <td><button class="btn btn-primary btn-sm" @click="buyProduct()">Buy</button></td>
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
                Products:[{}],
                myOrder:[{}]
            }
        },
        methods:{
            searchProducts(){
                window.axios.post('searchProducts').then(({ data }) => {
                if(!data.error){
                        this.Products = data.Mensaje;
                    }else{
                        this.ErrorInterno();
                    }
                });
            },
            buyProduct(IdProduct,ProductCode, ProductDescription, ProductCost){
                let dataE =  new FormData();
                dataE.append("IdProduct",IdProduct);
                dataE.append("ProductCode",ProductCode);
                dataE.append("ProductDescription",ProductDescription);
                dataE.append("ProductCost",ProductCost);
                window.axios.post('buyProduct',dataE).then(({ data }) => {
                if(!data.error){
                        /*window.location.href = data.Mensaje.processUrl;*/
                        window.open(
                        data.Mensaje.processUrl,
                        '_blank' // <- This is what makes it open in a new window.
                        );
                        console.log(data.Mensaje);
                    }else{
                        this.ErrorInterno();
                    }
                });
            },
            myOrders(){
                 window.axios.post('myOrders').then(({ data }) => {
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
            this.searchProducts();
            this.myOrders();
        }
    }
</script>