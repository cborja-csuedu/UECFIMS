<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $birthdate
 * @property string $local_center
 * @property string $address
 * @property string $zip_code
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $gender
 * @property string|null $civil_status
 * @property string|null $occupation
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone
 * @property \Carbon\Carbon|null $baptism_date
 * @property \Carbon\Carbon|null $membership_date
 * @property string|null $picture
 * @property string|null $esign
 * @property int $user_id
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Member extends Model
{
    protected $fillable = [
        'name',
        'birthdate',
        'local_center',
        'address',
        'zip_code',
        'phone',
        'email',
        'gender',
        'civil_status',
        'occupation',
        'emergency_contact_name',
        'emergency_contact_phone',
        'baptism_date',
        'membership_date',
        'picture',
        'esign',
        'user_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'baptism_date' => 'date',
            'membership_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
