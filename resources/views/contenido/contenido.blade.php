    @extends('principal')
    @section('contenido')

    @if(Auth::check())
            @if (Auth::user()->idrol == 1)
            <template v-if="menu==0">
                <venta></venta>
            </template>
            <template v-if="menu==1">
                <detalles></detalles>
            </template>
            <template v-if="menu==2">
                <pizza></pizza>
            </template>
            @elseif (Auth::user()->idrol == 2)
            <template v-if="menu==0">
                <venta></venta>
            </template>
            <template v-if="menu==1">
                <pizza></pizza>
            </template>
            @endif

    @endif
       
        
    @endsection