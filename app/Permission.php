<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Permission
 *
 * @property integer $id
 * @property string $alias
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\App\Permission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Permission whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Permission whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Permission extends Model
{
    protected $visible = ['id','name','alias','roles'];
    //
    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param String $role_alias
     * @return \App\Permission
     */
    public function assignRole($role_alias){
        if(!$this->roles->contains(Role::whereAlias($role_alias)->first()->id)){
            $this->roles()->attach(Role::whereAlias($role_alias)->firstOrFail());
        }
        return $this;
    }

    /**
     * @param String[] $role_aliases
     * @return \App\Permission
     */
    public function assignRoles($role_aliases){
        foreach($role_aliases as $role_alias){
            $this->assignRole($role_alias);
        }
        return $this;
    }

    /**
     * @param String $role_alias
     * @return \App\Permission
     */
    public function revokeRole($role_alias){
        $this->roles()->detach(Role::whereAlias($role_alias)->firstOrFail());
        return $this;
    }

    /**
     * @param String[] $role_aliases
     * @return \App\Permission
     */
    public function revokeRoles($role_aliases){
        foreach($role_aliases as $role_alias){
            $this->revokeRole($role_alias);
        }
        return $this;
    }
}
