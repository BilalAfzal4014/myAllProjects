<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportData extends Model
{
    protected $table = 'import_data';

    protected $fillable = [
        'company_id', 'actual_file_name', 'file_name', 'file_size', 'file_path', 'is_processed', 'is_deleted', 'process_date', 'created_by'
    ];
}
