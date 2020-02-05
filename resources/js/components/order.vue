<template>
    <div class="container">
        <div class="container-fluid">
            <h2>Products</h2>
            <table class="table">
                <tr>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Cost</th>
                    <th>Action</th>
                </tr>
                <tbody>
                    <tr v-for="P in Products">
                        <td>{{P.product_code}}</td>
                        <td>{{P.product_description}}</td>
                        <td>{{P.product_cost}}</td>
                        <td><button class="btn btn-primary btn-sm" @click="buyProduct(P.id,P.product_code,P.product_description,P.product_cost)">Buy</button></td>
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
                Products:[{}]
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
                        this.Products = data.Mensaje;
                    }else{
                        this.ErrorInterno();
                    }
                });
            },
            internalError(){
                alert("Internal Error");
            }
        },created(){
            this.searchProducts()
        }
    }
</script>