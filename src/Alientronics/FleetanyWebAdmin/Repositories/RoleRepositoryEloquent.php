<?php

namespace Alientronics\FleetanyWebAdmin\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Alientronics\CachedEloquent\Role;
use Lang;
use Kodeine\Acl\Models\Eloquent\Permission;

class RoleRepositoryEloquent extends BaseRepository implements RoleRepository
{

    protected $rules = [
        'name' => 'min:3|required',
        'slug' => 'min:3|required',
        'description'      => 'min:3|required',
        ];

    public function model()
    {
        return Role::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    public function results($filters = [])
    {
        $companies = $this->scopeQuery(function ($query) use ($filters) {
            
            $query = $query->select('roles.*');
            
            if (!empty($filters['name'])) {
                $query = $query->where('name', 'like', '%'.$filters['name'].'%');
            }
            if (!empty($filters['description'])) {
                $query = $query->where('description', 'like', '%'.$filters['description'].'%');
            }

            $query = $query->orderBy($filters['sort'], $filters['order']);
            
            return $query;
        })->paginate($filters['paginate']);
        
        return $companies;
    }
    
    public function validateRole($inputs, $idRole = null)
    {
        $errors = "";
        $role = Role::where(function ($query) use ($inputs) {
                        $query->where('name', $inputs['name'])
                            ->orWhere('slug', $inputs['slug'])
                            ->orWhere('description', $inputs['description']);
        });
        
        if (!empty($idRole)) {
            $role = $role->where('id', '<>', $idRole);
        }
        
        $role = $role->first();
        
        if (!empty($role)) {
            if ($role->name == $inputs['name']) {
                $errors[] = Lang::get('admin.roleexists');
            }
            if ($role->slug == $inputs['slug']) {
                return Lang::get('admin.slugexists');
            }
            if ($role->description == $inputs['description']) {
                return Lang::get('admin.descriptionexists');
            }
        }
        
        return "";
    }
    
    public function validatePermission($inputs, $idPermission = null)
    {
        $errors = "";
        $permission = Permission::where(function ($query) use ($inputs) {
                        $query->where('name', $inputs['permissiondialog_name'])
                            ->orWhere('description', $inputs['permissiondialog_description']);
        });
        
        if (!empty($idPermission)) {
            $permission = $permission->where('id', '<>', $idPermission);
        }
        
        $permission = $permission->first();
        
        if (!empty($permission)) {
            if ($permission->name == $inputs['name']) {
                $errors[] = Lang::get('admin.permissionexists');
            }
            if ($permission->description == $inputs['description']) {
                return Lang::get('admin.descriptionexists');
            }
        }
        
        return "";
    }
    
    public function updateRolePermissions($role_id, $permissions)
    {
        $role = Role::find($role_id);
        $role->syncPermissions($permissions);
    }
    
    public function createPermission($inputs)
    {
        $fields = [
            'name'        => $inputs['permissiondialog_name'],
            'description' => $inputs['permissiondialog_description']
        ];
        
        if (!empty($inputs['permissiondialog_inherit_id'])) {
            $fields['inherit_id'] = $inputs['permissiondialog_inherit_id'];
        }
        
        if (!empty($inputs['permissiondialog_slug'])) {
            $inputs['permissiondialog_slug'] = explode(",", $inputs['permissiondialog_slug']);
            $fields['slug'] = [];
            foreach ($inputs['permissiondialog_slug'] as $slug) {
                $fields['slug'][$slug] = true;
            }
        } else {
            $fields['slug'] = "";
        }
        
        Permission::create($fields);
    }
}
