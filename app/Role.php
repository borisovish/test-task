<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Role
 *
 * @property integer $id
 * @property string $alias
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    protected $visible = ['id','name','alias','permissions','users'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(){
        return $this->belongsToMany(User::class);
    }

    /**
     * @param String $user_id
     * @return \App\Role
     */
    public function assignUser($user_id){
        if(!$this->users->contains(User::whereId($user_id)->first()->id)){
            $this->users()->attach(User::whereId($user_id)->firstOrFail());
        }
        return $this;
    }

    /**
     * @param String[] $user_ids
     * @return \App\Role
     */
    public function assignUsers($user_ids){
        foreach($user_ids as $user_id){
            $this->assignUser($user_id);
        }
        return $this;
    }

    /**
     * @param String $user_id
     * @return \App\Role
     */
    public function revokeUser($user_id){
        $this->users()->detach(User::whereId($user_id)->firstOrFail());
        return $this;
    }

    /**
     * @param String[] $user_ids
     * @return \App\Role
     */
    public function revokeUsers($user_ids){
        foreach($user_ids as $user_id){
            $this->revokeUser($user_id);
        }
        return $this;
    }

    /**
     * @param String $permission_alias
     * @return \App\Role
     */
    public function assignPermission($permission_alias){
        if(!$this->permissions->contains(Permission::whereAlias($permission_alias)->first()->id)){
            $this->permissions()->attach(Permission::whereAlias($permission_alias)->firstOrFail());
        }
        return $this;
    }

    /**
     * @param String[] $permission_aliases
     * @return \App\Role
     */
    public function assignPermissions($permission_aliases){
        foreach($permission_aliases as $permission_alias){
            $this->assignPermission($permission_alias);
        }
        return $this;
    }

    /**
     * @param String $permission_alias
     * @return \App\Role
     */
    public function revokePermission($permission_alias){
        $this->permissions()->detach(Permission::whereAlias($permission_alias)->firstOrFail());
        return $this;
    }

    /**
     * @param String[] $permission_aliases
     * @return \App\Role
     */
    public function revokePermissions($permission_aliases){
        foreach($permission_aliases as $permission_alias){
            $this->revokePermission($permission_alias);
        }
        return $this;
    }
}
