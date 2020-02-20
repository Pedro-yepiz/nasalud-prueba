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
                        <i class="fa fa-align-justify"></i>  Nuevo Pedido
                    </div>

                    <!-- Detalle-->
                    <template>
                    <div class="card-body">
                        <div class="form-group row border">
                             <div class="col-md-4">
                                <label for=""><strong>Fecha pedido</strong></label>
                                <input type="date" class="form-control" v-model="fecha_pedido">
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <label for=""><strong>Numero pedido</strong></label>
                                <input type="text" class="form-control" v-model="numero_pedido" placeholder="Numero del Pedido">
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Pizzas</strong></label>
                                    <div class="form-inline">
                                        <button @click="abrirModal()" class="btn btn-primary"><i class="icon-folder-alt"></i></button>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Pizzas</th>
                                            <th>Tamaño</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.pizza"></td>
                                            <td v-text="detalle.tamano"></td>
                                            <td>
                                                <input v-model="detalle.precio" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detalle.cantidad" type="number" class="form-control">
                                            </td>
                                            <td>
                                                {{detalle.precio*detalle.cantidad}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{total=calcularTotal}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                NO hay Pizzas agregados
                                            </td>
                                        </tr>
                                    </tbody>                                    
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Pedido</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- Fin Detalle-->
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
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterioA">
                                            <option value="nombre">Nombre</option>>
                                        </select>
                                        <input type="text" v-model="buscarA" @keyup.enter="listarPizza(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarPizza(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Nombre</th>
                                            <th>Tamaño</th>
                                            <th>Precio</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="pizza in arrayPizza" :key="pizza.id">
                                            <td>
                                                <button type="button" @click="agregarDetalleModal(pizza)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                                </button>
                                            </td>
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
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
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
                fecha_pedido:"",
                numero_pedido:"",
                total:0.0,
                arrayDetalle : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorVenta : 0,
                errorMostrarMsjVenta : [],
                criterioA:'nombre',
                buscarA: '',
                arrayPizza: [],
                idpizza: 0,
                pizza: '',
                precio: 0,
                cantidad:0
            }
        },
        computed:{
            calcularTotal: function(){
                var resultado=0.0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad)
                }
                return resultado;
            }
        },
        methods : {
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].idpizza==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index, 1);
            },
            agregarDetalleModal(data =[]){
                let me=this;
                if(me.encuentra(data['id'])){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Esta pizza ya se encuentra agregada!',
                            })
                    }else{
                       me.arrayDetalle.push({
                            idpizza: data['id'],
                            pizza: data['nombre'],
                            tamano: data['tamaño'],
                            cantidad: 1,
                            precio: data['precio']
                        }); 
                    }
            },
            listarPizza (buscar,criterio){
                let me=this;
                var url= '/pizza?buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPizza = respuesta.pizzas.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarVenta(){
                if (this.validarVenta()){   return;       }                
                let me = this;
                axios.post('/pedido/registrar',{
                    'numero_pedido': this.numero_pedido,
                    'fecha': this.fecha_pedido,
                    'total' : this.total,
                    'data': this.arrayDetalle
                }).then(function (response) {
                    me.idpizza = 0;
                    me.fecha='';
                    me.numero_pedido='';
                    me.num_comprobante='';
                    me.impuesto=0.18;
                    me.total=0.0;
                    me.pizza='';
                    me.cantidad=0;
                    me.precio=0;
                    me.arrayDetalle=[];
                     swal(
                        'Registro Exitoso!',
                        'El pedido ha sido guardado con éxito.',
                        'success'
                        )
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validarVenta(){
                let me=this;
                me.errorVenta=0;
                me.errorMostrarMsjVenta =[];
                if (!me.fecha_pedido) me.errorMostrarMsjVenta.push("Ingrese fecha del pedido");
                if (!me.numero_pedido) me.errorMostrarMsjVenta.push("Ingrese el número de pedido");
                if (me.arrayDetalle.length<=0) me.errorMostrarMsjVenta.push("Ingrese Pizzas");

                if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

                return me.errorVenta;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
            }, 
            abrirModal(){               
                this.arrayPizza=[];
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varios artículos';
                this.listarPizza('','nombre');
            },
        },
        mounted() {
          
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
