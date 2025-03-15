<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class TasksTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tasks'); // Ensure this matches your database table name
        $this->setPrimaryKey('id'); // Set the primary key field
    }
}
