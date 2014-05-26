<?php

use Phinx\Migration\AbstractMigration;

class CreateMatrixColsTable extends AbstractMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = $this->table('matrix_cols', ['id' => 'col_id']);

        $table->addColumn('site_id', 'integer', ['signed' => false, 'null' => true]);
        $table->addColumn('field_id', 'integer', ['signed' => false, 'null' => true]);
        $table->addColumn('var_id', 'integer', ['signed' => false, 'null' => true]);
        $table->addColumn('col_name', 'string', ['limit' => 32, 'null' => true]);
        $table->addColumn('col_label', 'string', ['limit' => 50, 'null' => true]);
        $table->addColumn('col_instructions', 'text', ['null' => true]);
        $table->addColumn('col_type', 'string', ['limit' => 50, 'null' => true]);
        $table->addColumn('col_required', 'string', ['limit' => 1, 'null' => true]);
        $table->addColumn('col_search', 'string', ['limit' => 1, 'null' => true]);
        $table->addColumn('col_order', 'integer', ['signed' => false, 'null' => true]);
        $table->addColumn('col_width', 'string', ['limit' => 4, 'null' => true]);
        $table->addColumn('col_settings', 'text', ['null' => true]);
    
        $table->create();
    }
}
