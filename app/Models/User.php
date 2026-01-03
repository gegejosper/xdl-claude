<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'status',
        'email',
        'password',
        'device_id',
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
    // public function getAuthIdentifierName(){
    //     return 'username';
    // }

    public function primary_role_name(): ?string
    {
        return $this->roles->first()?->name; // get the first assigned role
    }

    public function role_level(): int
    {
        // $hierarchy = config('roles.hierarchy');
        // $role_name = $this->primary_role_name();
        $role = $this->roles->first(); // Spatie gives a collection of roles
        //dd($role->level);
        //dd($hierarchy[$role_name]);
        //return $hierarchy[$role_name] ?? 0;
        
        return $role->level ?? 0;
    }

    public function is_higher_than(User $other_user): bool
    {
        return $this->role_level() > $other_user->role_level();
    }
    public function dashboard_route(): string
    {
        $primary_role = $this->primary_role_name() ?? 'default';
        return match ( $primary_role) {
            'superadmin'   => route('admin.dashboard'),
            'admin' => route('admin.dashboard'),
            'cashier' => route('cashier.dashboard'),
            default   => route('home'),
        };
        // return redirect($user->dashboard_route());
        // 
    }

}
