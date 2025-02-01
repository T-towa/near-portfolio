<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameSelfIntroductionToIntroductionInProfilesTable extends Migration
{
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->renameColumn('self_introduction', 'introduction');
        });
    }

    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->renameColumn('introduction', 'self_introduction');
        });
    }
}
