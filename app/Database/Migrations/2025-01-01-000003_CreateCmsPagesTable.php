<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCmsPagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'page_slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'hero_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'hero_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'main_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'main_content' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'video_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('page_slug', true);
        $this->forge->createTable('cms_pages', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('cms_pages', true);
    }
}
