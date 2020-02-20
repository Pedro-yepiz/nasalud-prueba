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
                        <i class="fa fa-align-justify"></i> Detalles de Pedidos
                    </div>
                    <!-- Listado-->
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="pedidos.numero_pedido">Pedido</option>
                                      <option value="users.num_comprobante">Usuario</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarDetalle(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarDetalle(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Pedido</th>
                                        <th>Fecha</th>
                                        <th>Capturista</th>
                                        <th>Total Pedido</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="pedido in arrayPedidos" :key="pedido.id">
                                        <td>
                                            <button type="button" @click="verIngreso(pedido.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i> Detalles
                                            </button>
                                        </td>
                                        <td v-text="pedido.numero_pedido"></td>
                                        <td v-text="pedido.fecha"></td>
                                        <td v-text="pedido.usuario"></td>
                                        <td v-text="pedido.total"></td>
                                        <td>
                                            <div v-if="pedido.activo">
                                              <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>  
                                        </td>
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
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
                    </template>
                    <!--Fin Listado-->

                    <!-- Ver detalles -->
                    <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for=""><strong>Fecha Pedido</strong></label>
                                    <p v-text="fecha_pedido"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for=""><strong>Numero de Pedido</strong></label>
                                <p v-text="numero_pedido"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Capturista</strong></label>
                                    <p v-text="usuario"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Pizza</th>
                                            <th>Tamaño</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.nombre"></td>
                                            <td v-text="detalle.tamaño"></td>
                                            <td v-text="detalle.cantidad"></td>
                                            <th v-text="detalle.precio"></th>
                                            <td>
                                                {{detalle.precio*detalle.cantidad}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{total}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                NO hay artículos agregados
                                            </td>
                                        </tr>
                                    </tbody>                                    
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- fin ver detalles -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            
        </main>
</template>

<script>
    export default {
        data (){
            return {
                total:0.0,
                arrayPedidos : [],
                arrayDetalle : [],
                listado:1,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'pedidos.numero_pedido',
                buscar : '',
                fecha_pedido:"",
                numero_pedido:"",
                usuario:""
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
            listarDetalle (page,buscar,criterio){
                let me=this;
                var url= '/pedido?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPedidos = respuesta.pedidos.data;
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
                me.listarDetalle(page,buscar,criterio);
            },
            ocultarDetalle(){
                this.listado=1;
            },
            verIngreso(id){
                let me=this;
                me.listado=2;
                
                //Obtener los datos del pedido
                var arrayPedidosT=[];
                var url= '/pedido/obtenerCabecera?id=' + id;                
                    axios.get(url).then(function (response) {
                        var respuesta= response.data;
                        arrayPedidosT = respuesta.pedidos;
                        me.fecha_pedido = arrayPedidosT[0]['fecha'];
                        me.numero_pedido=arrayPedidosT[0]['numero_pedido'];
                        me.usuario=arrayPedidosT[0]['usuario'];
                        me.total = arrayPedidosT[0]['total'];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

                //Obtener los datos de los detalles 
                var urld= '/pedido/obtenerDetalles?id=' + id;                
                    axios.get(urld).then(function (response) {
                        console.log(response);
                        var respuesta= response.data;
                        me.arrayDetalle = respuesta.detalles;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });               
            },
        },
        mounted() {
            this.listarDetalle(1,this.buscar,this.criterio);
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
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }

</style>
