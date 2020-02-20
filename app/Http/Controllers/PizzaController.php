<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pizza;
class PizzaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $pizzas = Pizza::orderBy('pizzas.created_at', 'desc')->paginate(10);
        }
        else{
            $pizzas = Pizza::where('pizzas.'.$criterio, 'like', '%'. $buscar . '%')
                              ->orderBy('pizzas.created_at', 'desc')->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $pizzas->total(),
                'current_page' => $pizzas->currentPage(),
                'per_page'     => $pizzas->perPage(),
                'last_page'    => $pizzas->lastPage(),
                'from'         => $pizzas->firstItem(),
                'to'           => $pizzas->lastItem(),
            ],
            'pizzas' => $pizzas
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $pizza = new Pizza();
        $pizza->nombre = $request->nombre;
        $pizza->tamaño = $request->tamano;
        $pizza->precio = $request->precio_venta;
        $pizza->save();
    }
}
