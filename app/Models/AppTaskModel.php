<?php

namespace App\Models;

use CodeIgniter\Model;

class AppTaskModel extends Model
{
    protected $table            = 'tb_app_tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['app_id', 'task_name', 'wa_id', 'task_type', 'php_script', 'custom_query', 'message_template', 'body_message', 'message_footer'];

    // Dates
    protected $useTimestamps = false;
}
