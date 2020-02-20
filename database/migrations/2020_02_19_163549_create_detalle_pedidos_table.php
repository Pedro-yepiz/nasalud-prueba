<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idpedido')->unsigned();
            $table->foreign('idpedido')->references('id')->on('pedidos')->onDelete('cascade');
            $table->integer('idpizza')->unsigned();
            $table->foreign('idpizza')->references('id')->on('pizzas');
            $table->integer('cantidad');
            $table->decimal('precio', 11, 2);
            $table->boolean('activo')->default(1);   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_pedidos');
    }
}
