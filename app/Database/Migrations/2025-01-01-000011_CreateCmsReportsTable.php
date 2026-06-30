<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCmsReportsTable extends Migration
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
            'year' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'month' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => '#',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('cms_reports', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('cms_reports', true);
    }
}
