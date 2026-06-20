<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationModel extends Model
{
    protected $table            = 'tb_applications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['name', 'appsbee_api_key', 'db_host', 'db_user', 'db_pass', 'db_name', 'target_table', 'custom_query', 'message_template'];

    // Dates
    protected $useTimestamps = false;
}
