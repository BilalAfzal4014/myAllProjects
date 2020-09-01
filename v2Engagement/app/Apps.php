<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apps extends Model
{
    protected $table = 'app';
    protected $appends = ['logoUrl'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

    protected $fillable = [
        "name",
        "logo",
        "app_id",
        "description",
        "company_id",
        "ios_cert_live",
        "ios_cert_dev",
        "firebase_server_api_key",
        "ios_passphrase",
        "platform",
        "is_sandbox",
        "is_active",
        "is_deleted",
        "created_by",
        "modified_by",
    ];

    /**
     * @return string
     */
    public function getLogoUrlAttribute()
    {
        return asset("storage/uploads/app/{$this->logo}");
        
    }

    /**
     * @return bool
     */
    public function isSandbox()
    {
        return ((bool)$this->is_sandbox === true) ? true : false;
    }
}
