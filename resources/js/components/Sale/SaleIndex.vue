
<template>
    <div class="row card">
        <div class="cold-md-12 card">

        <div class="card-content">
           
            <div class="col-lg-12">
                <div class="category">
                    <!--<button type="button" @click="handleCreate" class="btn btn-primary">Agregar Cliente</button>-->
                    <input type="button" class="btn btn-success btn-xs" @click="openModelCreate" value="Agregar Venta"/>
                </div>
            </div>
             
            
            <div class="col-lg-12">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>N°</th>
                        <th>Fecha</th>
                        <th>Iva</th>
                        <th>Descuento</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                    <tr v-for="row in tableData" :key="row.id">
                        <td>{{ row.num }}</td>
                        <td>{{ row.sold_at }}</td>
                        <td>{{ row.iva }}</td>
                        <td>{{ row.discount }}</td>
                        <td>{{ row.total }}</td>
                        <td>
                            <button type="button" name="edit" class="btn btn-warning btn-xs edit" @click="openModelDetail(row)">Detalle</button>
                            <button type="button" name="edit" class="btn btn-primary btn-xs edit" @click="openModelEdit(row)">Editar</button>
                            <button type="button" name="delete" class="btn btn-danger btn-xs delete" @click="deleteData(row)">Eliminar</button>
                        </td>
                    </tr>
                </table>
            </div>
            
        </div>
        
        </div>
        <div v-if="myModel">
                <transition name="model" scrollable>
                    <div class="modal-mask">
                        <div class="modal-wrapper">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <!--<button type="button" class="close" @click="myModel=false" >Agregar Cliente</button>-->
                                        <h4 class="modal-title">{{dynamicTitle}} </h4>
                                    </div> 
                                    <div class="modal-body">
                                        <div  class="col-lg-12">
                                            <div class="form-group">
                                                <label> Seleccionar Cliente</label>
                                                <select style=" border: 1px solid #000000;border-radius: 5px;" v-model="client_id_selected">
                                                     <option v-for="option in listClients" v-bind:value="option.id"  v-bind:key="option.id">
                                                        {{ option.name }}
                                                    </option>
                                                </select>
                                                
                                            </div>
                                        </div>
                                        <div  class="col-lg-12">
                                            <div class="form-group">
                                                <label> Seleccionar Producto</label>
                                                <ValidationProvider rules="required"  v-slot="{ errors }">
                                                 <select style="border: 1px solid #000000;border-radius: 5px;" v-model="product_id_selected">
                                                    <option v-for="option in listProducts" v-bind:value="option.id"  v-bind:key="option.id">
                                                        {{ option.name }}
                                                    </option>
                                                </select>
                                                <span>{{ errors[0] }}</span>
                                                </ValidationProvider>
                                            </div>
                                        </div>
                                        <div  class="col-lg-6">
                                            <div class="form-group">
                                                <label> Cantidad (unidad)</label>
                                                <ValidationProvider name="quantity" rules="required"  v-slot="{ errors }">
                                                <input   type="number" class="form-control" v-model="sale.quantity" min="1" pattern="^[0-9]+" name="quantity"/>
                                                <span>{{ errors[0] }}</span>
                                                </ValidationProvider>
                                            </div>
                                        </div>
                                        <div  class="col-lg-6">
                                            <div class="form-group">
                                                <label> Descuento (%)</label>
                                                <ValidationProvider name="quantity" rules="required"  v-slot="{ errors }">
                                                <input type="number" class="form-control" v-model="discount" min="1" max="100" pattern="^[0-9]+" name="discount"/>
                                                <span>{{ errors[0] }}</span>
                                                </ValidationProvider>
                                            </div>
                                        </div>
                                        <div align="center">
                                            <input type="button" class="btn btn-success btn-xs" :disabled="noProductoStock" v-model="actionButton" @click.prevent="addProductToList">
                                            
                                        </div>
                                        <br>
                                         <div class="col-lg-12">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Acciones</th>
                                                </tr>
                                                <tr v-for="row in order" :key="row.id">
                                                    <td>{{ row.name }}</td>
                                                    <td>{{ row.quantity }}</td>
                                                    <td>{{ row.sub_total }}</td>
                                                    <td><button type="button" name="edit" class="btn btn-primary btn-xs edit" @click="openModelEditItem(row)">Editar</button>
                                                    <button type="button" name="delete" class="btn btn-danger btn-xs delete" @click="deleteItem(row)">x</button></td>
                                                </tr>
                                                <tr>
                                                    <th colspan=3>Sub Total</th>
                                                    <th colspan=2>$ {{sub_total}} </th>
                                                </tr>
                                                 <tr>
                                                    <th colspan=3>Descuento ({{discount}}%)</th>
                                                    <th colspan=2>$ {{discountTotal}} </th>
                                                </tr>
                                                <tr>
                                                    <th colspan=3>Iva</th>
                                                    <th colspan=2>$ {{iva}} </th>
                                                </tr>
                                                
                                                 <tr>
                                                    <th colspan=3>Total</th>
                                                    <th colspan=2>$ {{total}} </th>
                                                </tr>
                                            </table>
                                            <input type="button" class="btn btn-success btn-xs" v-model="actionOrderText" @click.prevent="actionForm">
                                            <button type="button" class="btn btn-danger btn-xs" @click="closeModel" >Cerrar</button>
                                        </div>
                                    </div>
                                </div>        
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
            <div v-if="myModelDetails">
                <transition name="model" scrollable>
                    <div class="modal-mask">
                        <div class="modal-wrapper">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <!--<button type="button" class="close" @click="myModel=false" >Agregar Cliente</button>-->
                                        <h4 class="modal-title"> Detalle de venta </h4>
                                    </div> 
                                    <div class="modal-body">
                                        <div  class="col-lg-12">
                                            <div class="form-group">
                                                <label> Cliente: {{listDataDetails.client_name}} </label>
                                            </div>
                                             <div class="form-group">
                                                <label> Fecha: {{listDataDetails.sold_at}} </label>
                                            </div>
                                        </div>
                                        
                                        <br>
                                         <div class="col-lg-12">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                </tr>
                                                <tr v-for="row in listProductDetails" :key="row.id">
                                                    <td>{{ row.product.name }}</td>
                                                    <td>{{ row.quantity }}</td>
                                                    <td>{{ row.sub_total }}</td>
                                                </tr>
                                                <tr>
                                                    <th colspan=2>Sub Total</th>
                                                    <th colspan=2>$ {{listDataDetails.sub_total}} </th>
                                                </tr>
                                                 <tr>
                                                    <th colspan=2>Descuento ({{listDataDetails.discount}}%)</th>
                                                    <th colspan=2>$ {{listDataDetails.discountTotal}} </th>
                                                </tr>
                                                <tr>
                                                    <th colspan=2>Iva</th>
                                                    <th colspan=2>$ {{listDataDetails.iva}} </th>
                                                </tr>
                                                
                                                 <tr>
                                                    <th colspan=2>Total</th>
                                                    <th colspan=2>$ {{listDataDetails.total}} </th>
                                                </tr>
                                            </table>
                                            <button type="button" class="btn btn-danger btn-xs" @click="myModelDetails=false" >Cerrar</button>
                                        </div>
                                    </div>
                                </div>        
                            </div>
                        </div>
                    </div>
                </transition>
            </div>



             <div v-if="modelEditItem">
                <transition name="model" scrollable>
                    <div class="modal-mask">
                        <div class="modal-wrapper">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <!--<button type="button" class="close" @click="myModel=false" >Agregar Cliente</button>-->
                                        <h4 class="modal-title"> {{ itemEdit.name}} </h4>
                                    </div> 
                                    <div class="modal-body">
                                        <div  class="col-lg-6">
                                            <div class="form-group">
                                                <label> Cantidad (unidad)</label>
                                                <ValidationProvider name="quantity" rules="required"  v-slot="{ errors }">
                                                <input   type="number" class="form-control" v-model="itemEdit.quantity" min="1" pattern="^[0-9]+" name="quantity"/>
                                                <span>{{ errors[0] }}</span>
                                                </ValidationProvider>
                                            </div>
                                        </div>
                                         <div  class="col-lg-12 center">
                                             <button type="button" class="btn btn-success btn-xs" :disabled="noProductoStockItemEdit" @click="editItem" >Modificar</button>
                                            <button type="button" class="btn btn-danger btn-xs" @click="closeModelEditItem" >Cerrar</button>
                                        </div>
                                        
                                    </div>
                                </div>        
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        <div>
  
</div>
    </div>
    
</template>
<script>
import Vue from 'vue'
import {Table, TableColumn, Select,Option, Loading} from 'element-ui'
import axios from 'axios'
import {WebUtils} from '../../lib/webUtils'


Vue.use(Table)
Vue.use(TableColumn)
Vue.use(Select)
Vue.use(Option)

Vue.use(Loading)

export default {
    data(){
        return{
            noProductoStock:false,
            noProductoStockItemEdit:false,
            myModel: false,
            myModelDetails: false,
            modelEditItem: false,
            actionButton:'Agregar',
            dynamicTitle:'Agregar Venta',
            actionOrderText:'Terminar Venta',
            dialogForm: false,
            productData:{
                id: null,
                name: null,
                price: 0,
                quantity:0
            },
            tableData:[],
            listClients:[],
            listProducts:[],
            optionSelect:{
                client_id:null,
                product_id:null
            },
            client_id_selected:0,
            product_id_selected:0,
            sale:{
                product_id:0,
                name:'',
                quantity:0,
                sub_total:0
            },
            order:[],
            order_id:null,
            sub_total:0,
            discount:0,
            discountTotal:0,
            total:0,
            iva:0,
            listDataDetails:{},
            listProductDetails:[],
            itemEdit: {
                id: null,
                name:null,
                price: null,
                quantity: null
            }
        }
    },
    methods:{
        getSales(){
            axios.get(WebUtils.api('sales'))
            .then(response=>{
                this.tableData = []
                let num = 1
                response.data.forEach(element => {
                    this.tableData.push({
                        id: element.id,
                        num: num ++,
                        client_id: element.client_id,
                        client_name: element.client.name + ' ' + element.client.last_name,
                        sold_at: element.sold_at,
                        iva: element.iva,
                        discount: element.discount,
                        total: element.total,
                        products: element.details
                    })
                });
            })
        },
        getProducts(){
            axios.get(WebUtils.api('products'))
            .then(response=>{
                this.listProducts = []
                response.data.forEach(element => {
                    if(element.quantity == 0)
                        return 

                    this.listProducts.push({
                        id: element.id,
                        name: element.name,
                        price: element.price,
                        quantity: element.quantity
                    })
                });
            })
        },
        getClients(){
            axios.get(WebUtils.api('clients'))
            .then(response=>{
                this.listClients = []
                response.data.forEach(element => {
                   if(element.status == 0)
                    return 
                    
                    this.listClients.push({
                        id: element.id,
                        name: element.name + ' ' + element.last_name,
                    })
                });
            })
        },
        addProductToList(){
            let prod = this.listProducts.find( product => product.id == this.product_id_selected )
            
            let objectIndex = this.order.findIndex(order => order.product_id == this.product_id_selected)
            if(objectIndex == -1){
                this.order.push({
                    product_id: this.product_id_selected,
                    name: prod.name,
                    price: prod.price,
                    discount: this.sale.discount,
                    quantity: parseInt(this.sale.quantity),
                    stock: parseInt(prod.quantity),
                    sub_total: (parseInt(this.sale.quantity) * prod.price)
                })
            }else {
                this.order[objectIndex].product_id = this.product_id_selected
                this.order[objectIndex].name = prod.name
                this.order[objectIndex].price = prod.price
                this.order[objectIndex].discount = this.sale.discount
                this.order[objectIndex].quantity = parseInt( this.order[objectIndex].quantity) + parseInt(this.sale.quantity)
                this.order[objectIndex].sub_total = (this.order[objectIndex].quantity * prod.price)
            }
            
            this.product_id_selected = 0
            this.sale.quantity = 0
            this.sale.name = ''
            this.sale.discount = 0
            this.sale.sub_total = 0

            this.total = 0

            let cal = new Promise((resolve, reject) => {
                    this.order.forEach(element => {
                        this.sub_total += element.sub_total
                    });
                resolve('Ok')
            })

           cal.then((x)=> {
               this.discountTotal = (this.sub_total * this.discount) /100
               this.iva = ((this.sub_total -  this.discountTotal) * 19)/100
               this.total = this.iva + (this.sub_total -  this.discountTotal)
                
           })
            
        },
        actionForm(){
            this.actionOrderText == 'Terminar Venta' ? this.registerOrder() : this.updateOrder()
        },
        registerOrder(){
            axios.post(WebUtils.api('sales'),
            {
                client_id: this.client_id_selected,
                iva: this.iva,
                discount: this.discount,
                total: this.total,
                products: this.order
            }
            ).then(response => {
                this.$swal("Aviso", 'Venta realizada con éxito')
                 this.closeModel()
                 this.getSales()
                 this.getProducts()
                this.getClients()
            })
        },
        updateOrder(){
            axios.patch(WebUtils.api('sales/') + this.order_id,
            {
                id: this.order_id,
                client_id: this.client_id_selected,
                iva: this.iva,
                discount: this.discount,
                total: this.total,
                products: this.order
            }
            ).then(response => {
                this.$swal("Aviso", 'Venta modificada con éxito')
                this.closeModel()
                this.getSales()
                this.getProducts()
                this.getClients()
                this.order_id = null
            })
        },
        openModelCreate(){
            this.myModel = true
            this.actionButton = 'Agregar'
            this.dynamicTitle = 'Agregar Venta'
            this.actionOrderText = 'Terminar Venta'
        },
        openModelEdit(row){
            this.myModel = true
            this.dynamicTitle = 'Editar Venta'
            this.actionOrderText = 'Modificar Venta'

            this.client_id_selected = row.client_id
            this.order_id = row.id

            row.products.forEach(element => {
                this.order.push({
                    product_id: element.product_id,
                    name: element.product.name,
                    quantity: parseInt(element.quantity),
                    stock:  parseInt(element.product.quantity),
                    price: element.product.price,
                    sub_total: element.sub_total
                })
            });

             //Se repíte
            let sub_total_row = 0
            row.products.forEach(element => {
                sub_total_row += element.sub_total
            });

            this.sub_total = sub_total_row
            this.discount = row.discount
            this.discountTotal = (sub_total_row * row.discount)/100
            this.iva = row.iva 
            this.total = row.total

            this.productData.id = row.id
            this.productData.name = row.name
            this.productData.price= row.price
            this.productData.quantity = row.quantity
        },
        openModelDetail(row){
            this.myModelDetails = true
            
            let sub_total_row = 0
            row.products.forEach(element => {
                sub_total_row += element.sub_total
            });

            this.listProductDetails = row.products

            this.listDataDetails = {
                sold_at: row.sold_at,
                client_name: row.client_name,
                sub_total: sub_total_row,
                discount: row.discount,
                discountTotal: (sub_total_row * row.discount)/100,
                iva:row.iva,
                total: row.total
            }
        },
        openModelEditItem(row){
            this.modelEditItem = true

            this.itemEdit = {
                product_id: row.product_id,
                name:row.name,
                price: row.price,
                quantity: row.quantity,
                stock: row.stock
            }
        },
        closeModelEditItem(){
            this.modelEditItem = false
        },
        editItem(){
            this.modelEditItem = false
            let objectIndex = this.order.findIndex(obj => obj.product_id == this.itemEdit.product_id)
            if(this.itemEdit.quantity == 0){
                this.order.splice(objectIndex,1)
            }else{
                this.order[objectIndex].quantity = this.itemEdit.quantity
                this.order[objectIndex].sub_total = this.itemEdit.quantity * this.itemEdit.price
            }

            this.setValueGeneral()
        },
        closeModel(){
            this.myModel = false

            this.client_id_selected = null
            this.discount = 0

            this.order = []

            this.sub_total = 0
            this.discountTotal = 0
            this.iva = 0
            this.total = 0

            this.product_id_selected = 0
            this.sale.quantity = 0
            this.noProductoStock = false

            this.productData.id = null
            this.productData.name =null
            this.productData.price= null
            this.productData.quantity = null
        },
        deleteItem(row){
            let objectIndex = this.order.findIndex(obj => obj.product_id == row.product_id)
            this.order.splice(objectIndex,1)

            this.setValueGeneral()
              
        },
        deleteData(row){
            this.$swal({
                title: '¿Esta Seguro?',
                type:'warning',
                icon: 'question',
                class: 'swal2-container',
                shoeCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                showCloseButton: true,
                showLoaderOnConfirm: true
            }).then(async (result) => {
                if(result.value){
                    axios.delete(WebUtils.api('sales') + '/' + row.id)
                        .then(response => {
                            if(response.status == 200){
                                this.$swal("Aviso", 'Eliminado con éxito')
                                this.getSales()
                            }
                        })
                }
            })
        },
        setValueGeneral(){
            
            let sub_total_row = 0
            this.order.forEach(element => {
                sub_total_row += element.sub_total
            });

            this.sub_total = sub_total_row
            this.discountTotal = (sub_total_row * this.discount) /100
            this.iva = ((sub_total_row -  this.discountTotal) * 19)/100
            this.total = this.iva + (sub_total_row -  this.discountTotal)
        }
    },
    watch:{
        'discount'(){
             this.setValueGeneral()
        },
        'sale.quantity'(){
            this.product_id_selected

            let product = this.listProducts.find(product => product.id == this.product_id_selected)
            let orderIndex = this.order.findIndex(order => order.product_id == this.product_id_selected)
            this.noProductoStock = product.quantity < (parseInt(this.sale.quantity) +  parseInt(this.order[orderIndex].quantity))
        },
        'itemEdit.quantity'(){
          this.noProductoStockItemEdit = this.itemEdit.quantity > this.itemEdit.stock   
        }

    },
    created(){
        this.getProducts()
        this.getClients()
        this.getSales()
    }
}
</script>
<style scoped>

    .swall-overlay {
        z-index: 100005 !important;
    }
    .swal-modal {
        z-index: 99999 !important;
    }

    .modal-dialog{
        overflow-y: initial !important
    }

    .modal-body{
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }

    .modal-wrapper{
        display:table-cell;
        vertical-align: middle;
    }

    .modal-mask {
     position: fixed;
     z-index: 9998;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     background-color: rgba(0, 0, 0, .5);
     display: table;
     transition: opacity .3s ease;
   }
</style>