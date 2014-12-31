<?php echo "<?php\n"; ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
		// Creates the permissions table
		Schema::create('permissions', function ($table) {
			$table->bigIncrements('id')->unsigned();
			$table->string('name')->unique();
			$table->string('display_name');
			$table->string('permission');
			$table->timestamps();
			$table->softDeletes();
		});

        // Creates the assigned_permissions (Many-to-Many relation) table
        Schema::create('assigned_permissions', function ($table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
			$table->bigInteger('permission_id')->unsigned();
			$table->bigInteger('object_id')->unsigned();
			$table->string('object_class');
            $table->foreign('user_id')->references('id')->on('{{ \Illuminate\Support\Facades\Config::get('auth.table') }}')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')
				->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::table('assigned_permissions', function (Blueprint $table) {
            $table->dropForeign('assigned_permissions_user_id_foreign');
            $table->dropForeign('assigned_permissions_permission_id_foreign');
        });
        Schema::drop('assigned_permissions');
        Schema::drop('permissions');
    }

}
