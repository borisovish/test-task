<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('advcampaign_id')->nullable(); //id магазина
            $table->string('order_id',255)->nullable(); //id заказа в магазине
            $table->string('order_payment', 50)->nullable();//
            $table->integer('cart')->nullable();//
            $table->string('currency', 50)->nullable();//валюта
            $table->string('status', 50)->nullable();//статус заказа
            $table->timestamp('action_date');//время создания заказа
            $table->mediumText('description')->default('');//
            $table->timestamps();
            $table->index('advcampaign_id');
            $table->index('currency');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('api_datas');
    }
}
/*
Описание структуры xml
<advcampaign_id> - id магазина
<order_id> - id заказа в магазине
<status> - статус заказа
<cart> - сумма заказа
<currency> - валюта
<action_date> - время создания заказа

Описание структуры json
merchant_id - id магазина
id - id заказа в магазине
state - статус заказа
order_payment - сумма заказа
currency - валюта
created_at - время создания заказа

Предоставленные данные являются обязательными для сохранения в БД.
 */