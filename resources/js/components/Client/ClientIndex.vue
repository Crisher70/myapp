
<template>
    <div class="row card">
        <div class="cold-md-12 card">

        <div class="card-content">
           
            <div class="col-lg-12">
                <div class="category">
                    <!--<button type="button" @click="handleCreate" class="btn btn-primary">Agregar Cliente</button>-->
                    <input type="button" class="btn btn-success btn-xs" @click="openModelCreate" value="Agregar cliente"/>
                </div>
            </div>
             
            
            <div class="col-lg-12">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>N°</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    <tr v-for="row in tableData" :key="row.id">
                        <td>{{ row.num }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.last_name }}</td>
                        <td>{{ row.status_name }}</td>
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
                                            <input type="text" class="form-control" v-model="clientData.name" name="name" />
                                             <span>{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </div>
                                        <div class="form-group">
                                            <label> Apellido</label>
                                            <ValidationProvider name="last_name" rules="required"  v-slot="{ errors }">
                                            <input type="text" class="form-control" v-model="clientData.last_name" name="last_name"/>
                                             <span>{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </div>
                                         <div class="form-group">
                                            <label> Estado</label>
                                            <el-radio-group v-model="clientData.status">
                                                <el-radio :label="true"> Activado</el-radio>
                                                <el-radio :label="false"> Desactivado</el-radio>
                                            </el-radio-group>
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
            dynamicTitle:'Agregar cliente',
            dialogForm: false,
            clientData:{
                id: null,
                name: null,
                last_name: null,
                status: true
            },
            tableData:[],
        }
    },
    methods:{
        handleCreate(){
            this.$router.push('clients/add')
        },
        getClients(){
            axios.get(WebUtils.api('clients'))
            .then(response=>{
                this.tableData = []
                let num = 1
                response.data.forEach(element => {
                    this.tableData.push({
                        id: element.id,
                        num: num++,
                        name: element.name,
                        last_name: element.last_name,
                        status: element.status, 
                        status_name: element.status == 1 ? 'Activado' : 'Desactivado' 
                    })
                });
            })
        },
        addClient(){
            if(this.clientData.name == null && this.clientData.last_name == null)
                return
                
            axios.post(WebUtils.api('clients'), this.clientData)
                .then(response =>{
                    this.$swal("Aviso", 'Agreado con éxito')
                    this.closeModel()
                    this.getClients()
                })
        },
        editClient(){
            if(this.clientData.name == null && this.clientData.last_name == null)
                return
                
            axios.patch(WebUtils.api('clients') + '/' + this.clientData.id,  this.clientData)
                .then(response =>{
                    this.$swal("Aviso", 'Modificación realizada con éxito')
                    this.closeModel()
                    this.getClients()
                })
        },
        register(){
            this.actionButton == 'Agregar' ? this.addClient() : this.editClient()
        },
        openModelCreate(){
            this.myModel = true
            this.actionButton = 'Agregar',
            this.dynamicTitle = 'Agregar cliente'
        },
        openModelEdit(row){
            this.myModel = true
            this.actionButton = 'Editar',
            this.dynamicTitle = 'Editar cliente'

            this.clientData.id = row.id
            this.clientData.name = row.name
            this.clientData.last_name = row.last_name
            this.clientData.status = row.status == 1 ? true :false
        },
        closeModel(){
            this.myModel = false
            this.clientData.id = null
            this.clientData.name = null
            this.clientData.last_name = null
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
                    axios.delete(WebUtils.api('clients') + '/' + row.id)
                        .then(response => {
                            if(response.status == 200){
                                this.$swal("Aviso", 'Eliminado con éxito')
                                this.getClients()
                            }
                        })
                }
            })
        }
    },
    created(){
        this.getClients()
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