<?php

namespace Tests\Unit;

use Tests\UnitTestCase;
use Alientronics\FleetanyWebAttributes\Repositories\AttributeRepositoryEloquent;
use Illuminate\Pagination\LengthAwarePaginator;
use Kodeine\Acl\Models\Eloquent\Role;
use Alientronics\FleetanyWebAdmin\Repositories\RoleRepositoryEloquent;
use Kodeine\Acl\Models\Eloquent\Permission;

class RoleRepositoryEloquentTest extends UnitTestCase
{

    public function testCreatePermission()
    {
        $inputs = [];
        $inputs['permissiondialog_name'] = 'permission.test';
        $inputs['permissiondialog_description'] = 'Permission test';
        $inputs['permissiondialog_inherit_id'] = "";
        $inputs['permissiondialog_slug'] = "create,view";
        
        $roleRepo = new RoleRepositoryEloquent();
        $roleRepo->createPermission($inputs);
        
        $permission = Permission::first();

        $this->assertEquals($permission->name, $inputs['permissiondialog_name']);
        $this->assertEquals($permission->description, $inputs['permissiondialog_description']);
        $this->assertNull($permission->inherit_id, $inputs['permissiondialog_inherit_id']);
        $this->assertEquals($permission->slug, '{"create":true,"view":true}');
    }
    
    public function testUpdateRolePermissions()
    {
        $roleAdmin = new Role();
        $roleAdmin->name = 'Administrator';
        $roleAdmin->slug = 'administrator';
        $roleAdmin->description = 'manage administration privileges';
        $roleAdmin->save();
    
        $permissionsAfter = $roleAdmin->getPermissions();
        
        $permissions = Permission::get();
        $roleRepo = new RoleRepositoryEloquent();
        $roleRepo->updateRolePermissions($permissions);
    
        $this->assertNotEquals($permissionsAfter, $permissions);
        $this->assertEquals($roleAdmin->getPermissions(), $permissions);
    }
    
}
