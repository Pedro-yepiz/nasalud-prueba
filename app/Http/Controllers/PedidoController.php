<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;
use App\DetallePedido;
use Illuminate\Support\Facades\DB;
class PedidoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $pedidos = Pedido::leftjoin('users','pedidos.iduser','=','users.id')
                                ->select('pedidos.id','pedidos.numero_pedido','pedidos.fecha','pedidos.total','pedidos.activo','users.usuario')
                                ->orderBy('pedidos.created_at', 'desc')
                                ->paginate(10);
        }
        else{
            $pedidos = Pedido::leftjoin('users','pedidos.iduser','=','users.id')
                               ->select('pedidos.id','pedidos.numero_pedido','pedidos.fecha','pedidos.total','pedidos.activo','users.usuario')
                               ->where($criterio, 'like', '%'. $buscar . '%')
                               ->orderBy('pedidos.created_at', 'desc')
                               ->paginate(10);
        }        

        return [
            'pagination' => [
                'total'        => $pedidos->total(),
                'current_page' => $pedidos->currentPage(),
                'per_page'     => $pedidos->perPage(),
                'last_page'    => $pedidos->lastPage(),
                'from'         => $pedidos->firstItem(),
                'to'           => $pedidos->lastItem(),
            ],
            'pedidos' => $pedidos
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $pedidos = new Pedido();
            $pedidos->numero_pedido = $request->numero_pedido;
            $pedidos->fecha = $request->fecha;
            $pedidos->total = $request->total;
            $pedidos->iduser = \Auth::user()->id;
            $pedidos->save();

            
            $detalles = $request->data; //Array de detalles

            //Recorro todos los elementos

            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetallePedido();
                $detalle->idpedido = $pedidos->id;
                $detalle->idpizza = $det['idpizza'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->save();
            }   

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        } 
    }

    
    public function obtenerCabecera(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;

        $pedidos = Pedido::leftjoin('users','pedidos.iduser','=','users.id')
                    ->select('pedidos.id','pedidos.numero_pedido','pedidos.fecha','pedidos.total','pedidos.activo','users.usuario','pedidos.total')
                    ->where('pedidos.id', $id)
                    ->get()->take(1);
         
        return ['pedidos' => $pedidos];
    }

    public function obtenerDetalles(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;

        $detalles = DetallePedido::join('pizzas','detalle_pedidos.idpizza','=','pizzas.id')
                ->select('detalle_pedidos.cantidad','detalle_pedidos.precio','pizzas.nombre','pizzas.tamaño')
                ->where('detalle_pedidos.idpedido',$id)
                ->orderBy('detalle_pedidos.created_at', 'desc')
                ->get();
         
        return ['detalles' => $detalles];
    }
}
