<?php

namespace App;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
     use HasRoles;

     protected $dates = [
         'created_at',
         'updated_at'
     ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','company_key', 'updated_by', 'created_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function segments()
    {
        return $this->hasMany(Segment::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attribute_data()
    {
        return $this->hasMany(AttributeData::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newsfeeds()
    {
        return $this->hasMany(NewsFeed::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apps()
    {
        return $this->hasMany(Apps::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cappingrules()
    {
        return $this->hasMany(CampaignCapRule::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emails()
    {
        return $this->hasMany(EmailList::class, 'company_id');
    }

    /**
     * @param string $type
     * @return mixed
     */
    public function capRule($type)
    {
        $rules = $this->cappingrules;
        $method = "is" . ucfirst($type);

        if ($rules->count() > 0) {
            return $rules->filter(function ($rule) use($method) {
                return ($rule->{$method}() === true) ? $rule : null;
            })->first();
        }

        return $rules;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBlacklistEmails()
    {
        return $this->emails->filter(function ($email) {
            return ($email->rec_type == 'blacklist') ? $email : null;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWhitelistEmails()
    {
        return $this->emails->filter(function ($email) {
            return ($email->rec_type == 'whitelist') ? $email : null;
        });
    }

//    public function setPasswordAttribute($password) {
//        $this->attributes['password'] = bcrypt($password);
//    }

    // public function setCreatedByAttribute( $createdBy ) {
    //     $this->attributes['created_by'] = 1;
    // }
    //
    // public function setUpdatedByAttribute( $updatedBy ) {
    //     $this->attributes['updated_by'] = 1;
    // }

    // public function setCreatedAtAttribute() {
    //     $this->attributes['created_at'] = Carbon::now();
    // }
    //
    // public function setUpdatedAtAttribute() {
    //     $this->attributes['updated_at'] = Carbon::now();
    // }
}
