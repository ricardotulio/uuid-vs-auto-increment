<?php

use Phinx\Migration\AbstractMigration;

class CreatePersonTableMigration extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('person', array('id' => false));
        $table->addColumn('id', 'binary', array('limit' => '16'))
              ->addColumn('name', 'string')
              ->addColumn('created', 'datetime')
              ->addColumn('updated', 'datetime', array('null' => true))
              ->create();
    }

    public function down()
    {
        $this->dropTable('person');
    }
}
