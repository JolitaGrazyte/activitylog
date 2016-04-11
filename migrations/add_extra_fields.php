<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFields extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        collect(config('activitylog.logsActivity'))->each(function ($item) {

           Schema::table($item, function (Blueprint $table) use ($item) {

              if (!Schema::hasColumn($item, 'causes_activity_id')) {
                  $table->integer('causes_activity_id');
              }

              if (!Schema::hasColumn($item, 'causes_activity_type')) {
                  $table->string('causes_activity_type');
              }

          });

        });

        collect(glob(database_path('/migrations/*_add_extra_fields.php')))->each(function ($file) {
            unlink($file);
        });
    }
}
