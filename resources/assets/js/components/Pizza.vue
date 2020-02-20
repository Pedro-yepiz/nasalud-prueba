<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <!-- <li class="breadcrumb-item"><a href="/">Escritorio</a></li> -->
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Pizzas
                        <div class="pull-right">  
                            <button type="button" @click="abrirModal('pizza','registrar')" class="btn btn-primary">
                                <i class="icon-plus"></i>&nbsp;Nueva Pizza
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarPizza(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPizza(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tamaño</th>
                                    <th>Precio Venta</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="pizza in arrayPizza" :key="pizza.id">
                                    <td v-text="pizza.nombre"></td>
                                    <td v-text="pizza.tamaño"></td>
                                    <td v-text="pizza.precio"></td>
                                    <td>
                                        <div v-if="pizza.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>                                        
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de pizza">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tamaño</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="tamano">
                                            <option value="0" disabled>Seleccione</option>
                                            <option value="Chica">Chica</option>
                                            <option value="Mediana">Mediana</option>
                                            <option value="Grande">Grande</option>
                                        </select>                                         
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="precio_venta" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div v-show="errorPizza" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPizza" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPizza()">Guardar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                tamano : 0,
                nombre : '',
                precio_venta : 0,
                arrayPizza : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPizza : 0,
                errorMostrarMsjPizza : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            listarPizza (page,buscar,criterio){
                let me=this;
                var url= '/pizza?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPizza = respuesta.pizzas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarPizza(page,buscar,criterio);
            },
            registrarPizza(){
                if (this.validarPizza()){  return;   }                
                let me = this;
                axios.post('/pizza/registrar',{
                    'tamano': this.tamano,
                    'nombre': this.nombre,
                    'precio_venta': this.precio_venta
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarPizza(1,'','nombre');
                    swal(
                        'Registro Exitoso!',
                        'El registro ha sido guardado con éxito.',
                        'success'
                        )
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validarPizza(){
                this.errorPizza=0;
                this.errorMostrarMsjPizza =[];
               
                if (!this.nombre) this.errorMostrarMsjPizza.push("El nombre de la pizza no puede estar vacío.");
                if (this.tamano==0) this.errorMostrarMsjPizza.push("Seleccione tamaño de la pizza.");
                if (!this.precio_venta) this.errorMostrarMsjPizza.push("El precio de la pizza debe ser un número y no puede estar vacío.");

                if (this.errorMostrarMsjPizza.length) this.errorPizza = 1;

                return this.errorPizza;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.tamano = 0;
                this.nombre = '';
                this.precio_venta = 0;
		        this.errorPizza=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "pizza":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Pizza';
                                this.tamano=0;
                                this.nombre= '';
                                this.precio_venta=0;
                                this.tipoAccion = 1;
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarPizza(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
