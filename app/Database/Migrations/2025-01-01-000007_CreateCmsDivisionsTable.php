<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCmsDivisionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'image_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'color_class' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'bg-white text-gray-800',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('cms_divisions', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('cms_divisions', true);
    }
}
