<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAttribute extends Model
{
    protected $table = 'user_attribute';
    protected $primaryKey = 'row_id';
    public $timestamps = false;
    protected $fillable = [
        "row_id",
        "company_id",
        "user_id",
        "app_name",
        "app_id",
        "device_type",
        "username",
        "firstname",
        "lastname",
        "device_token",
        "fire_base_key",
        "user_token",
        "email",
        "timezone",
        "latitude",
        "longitude",
        "country",
        "lang",
        "version",
        "build",
        "last_login",
        "is_login",
        "is_active",
        "locked",
        "enabled",
        "enable_notification",
        "email_notification",
        "test_mode",
        "is_import"
    ];
}
