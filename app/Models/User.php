<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Auth\MustVerifyEmail as AuthMustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, AuthMustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'instansi_id',
        'referrer_id',
        'referrer_type',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'is_nakes',
        'status',
        'referral_code',
        'last_activity',
        'header',
        'is_support_header',
        'sender_name',
        'google2fa_secret',
        'google2fa_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['type_user'];

    // simpan kode lokal dalam huruf kapital
    // public function setKodeLokalAttribute($value)
    // {
    //     $this->attributes['kode_lokal'] = strtoupper($value);
    // }

    // Menentukan tipe user berdasarkan instansi_id
    protected function typeUser(): Attribute
    {
        return Attribute::get(fn() => $this->instansi_id ? 'nakes' : 'non-nakes');
    }

    // Get Role
    public function getRole()
    {
        return $this->roles->first()?->name;
    }

    // Get Instansi
    public function getInstansi()
    {
        return $this->instansi?->name;
    }

    public function getInstansiVerified()
    {
        return $this->instansi?->is_verified;
    }

    public function isSupportHeader()
    {
        if ($this->is_nakes && $this->instansi) {
            return $this->instansi->is_support_header;
        }

        return $this->is_support_header;
    }

    // Instansi Relation
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    // Testimoni
    public function testimoni(): HasOne
    {
        return $this->hasOne(Testimoni::class, 'user_id');
    }

    // Patient
    public function patientsCreated()
    {
        return $this->hasMany(Patient::class, 'created_by');
    }

    // Antro
    public function antroCreated()
    {
        return $this->hasMany(AntroPatient::class, 'created_by');
    }

    public function pointBatch(): HasMany
    {
        return $this->hasMany(PointBatch::class, 'user_id');
    }
}
