
<template>
    <div class="row card">
        <div class="cold-md-12 card">

        <div class="card-content">
           
            <div class="col-lg-12">
                <div class="category">
                    <!--<button type="button" @click="handleCreate" class="btn btn-primary">Agregar Cliente</button>-->
                    <input type="button" class="btn btn-success btn-xs" @click="openModelCreate" value="Agregar Producto"/>
                </div>
            </div>
             
            
            <div class="col-lg-12">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>N°</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                    <tr v-for="row in tableData" :key="row.id">
                        <td>{{ row.num }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.price }}</td>
                        <td>{{ row.quantity }}</td>
                        <td><button type="button" name="edit" class="btn btn-primary btn-xs edit" @click="openModelEdit(row)">Editar</button>
                        <button type="button" name="delete" class="btn btn-danger btn-xs delete" @click="deleteData(row)">Eliminar</button></td>
                    </tr>
                </table>
            </div>
            
        </div>
        
        </div>
        <div v-if="myModel">
                <transition name="model">
                    <div class="modal-mask">
                        <div class="modal-wrapper">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <!--<button type="button" class="close" @click="myModel=false" >Agregar Cliente</button>-->
                                        <h4 class="modal-title">{{dynamicTitle}} </h4>
                                    </div> 
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label> Nombre</label>
                                            <ValidationProvider name="name" rules="required"  v-slot="{ errors }">
                                            <input type="text" class="form-control" v-model="productData.name" name="name" />
                                             <span>{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </div>
                                        <div class="form-group">
                                            <label> Precio</label>
                                            <ValidationProvider name="price" rules="required"  v-slot="{ errors }">
                                            <input type="number" class="form-control" v-model="productData.price" min="1" pattern="^[0-9]+" name="price"/>
                                             <span>{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </div>
                                         <div class="form-group">
                                           <label> Cantidad</label>
                                            <ValidationProvider name="quantity" rules="required"  v-slot="{ errors }">
                                            <input type="number" class="form-control" v-model="productData.quantity" min="1" pattern="^[0-9]+" name="quantity"/>
                                             <span>{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </div>
                                        <div align="center">
                                            <input type="button" class="btn btn-success btn-xs" v-model="actionButton" @click.prevent="register">
                                            <button type="button" class="btn btn-danger btn-xs" @click="myModel=false" >Cerrar</button>
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
import {Table, TableColumn, Select,Option, Loading, Radio,RadioButton,RadioGroup} from 'element-ui'
import swal from 'sweetalert2'
import axios from 'axios'
import {WebUtils} from '../../lib/webUtils'


Vue.use(Table)
Vue.use(TableColumn)
Vue.use(Select)
Vue.use(Option)
Vue.use(Radio)
Vue.use(RadioButton)
Vue.use(RadioGroup)

Vue.use(Loading)

export default {
    data(){
        return{
            myModel: false,
            actionButton:'Agregar',
            dynamicTitle:'Agregar Producto',
            dialogForm: false,
            productData:{
                id: null,
                name: null,
                price: 0,
                quantity:0
            },
            tableData:[]
        }
    },
    methods:{
        getProducts(){
            axios.get(WebUtils.api('products'))
            .then(response=>{
                this.tableData = []
                let num = 1;
                response.data.forEach(element => {
                    this.tableData.push({
                        id: element.id,
                        num: num++,
                        name: element.name,
                        price: element.price,
                        quantity: element.quantity
                    })
                });
            })
        },
        addProduct(){
            if(this.productData.name == null && this.productData.price > null && this.productData.quantity > null)
                return
            
            axios.post(WebUtils.api('products'), this.productData)
                .then(response =>{
                    this.$swal("Aviso", 'Agreado con éxito')
                    this.closeModel()
                    this.getProducts()
                })
        },
        editProduct(){
            if(this.productData.name == null && this.productData.price > null && this.productData.quantity > null)
                return
                
            axios.patch(WebUtils.api('products') + '/' + this.productData.id,  this.productData)
                .then(response =>{
                    this.$swal("Aviso", 'Modificación realizada con éxito')
                    this.closeModel()
                    this.getProducts()
                })
        },
        register(){
            this.actionButton == 'Agregar' ? this.addProduct() : this.editProduct()
        },
        openModelCreate(){
            this.myModel = true
            this.actionButton = 'Agregar',
            this.dynamicTitle = 'Agregar producto'
        },
        openModelEdit(row){
            this.myModel = true
            this.actionButton = 'Editar',
            this.dynamicTitle = 'Editar producto'

            this.productData.id = row.id
            this.productData.name = row.name
            this.productData.price= row.price
            this.productData.quantity = row.quantity
        },
        closeModel(){
            this.myModel = false
            this.productData.id = null
            this.productData.name =null
            this.productData.price= null
            this.productData.quantity = null
        },
        deleteData(row){
            this.$swal({
                title: '¿Esta Seguro?',
                type:'warning',
                icon: 'question',
                shoeCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                showCloseButton: true,
                showLoaderOnConfirm: true
            }).then(async (result) => {
                if(result.value){
                    axios.delete(WebUtils.api('products') + '/' + row.id)
                        .then(response => {
                            if(response.status == 200){
                                this.$swal("Aviso", 'Eliminado con éxito')
                                this.getProducts()
                            }
                        })
                }
            })
        }
    },
    created(){
        this.getProducts()
    }
}
</script>
<style scoped>
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