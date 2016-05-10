<?php

use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuperAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        User::destroy(1);
        $user = new User;
        $user->id = 1;
        $user->name = 'Super User';
        $user->email = 'super@example.com';
        $user->password = bcrypt('secret');
        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::destroy(1);
    }
}
