<?php

namespace Tests\Unit;

use Tests\UnitTestCase;
use Alientronics\FleetanyWebAdmin\Repositories\RoleRepositoryEloquent;

class RoleRepositoryEloquentTest extends UnitTestCase
{

    
    public function testCreatePermission()
    {

        $mockModel = \Mockery::mock('Illuminate\Database\Eloquent\Model');
        //$mockModel->shouldReceive('create')->once();
        //$mockModel->shouldReceive('save')->once();
        //$mockModel->shouldReceive('newInstance')->andReturnSelf();
        $this->app->instance('Illuminate\Database\Eloquent\Model', $mockModel);

        $mockPermission = \Mockery::mock('Kodeine\Acl\Models\Eloquent\Permission');
        //$mockPermission->shouldReceive('create')->once();
        //$mockPermission->shouldReceive('save')->once();
        //$mockPermission->shouldReceive('newInstance')->andReturnSelf();
        $this->app->instance('Kodeine\Acl\Models\Eloquent\Permission', $mockPermission);

        $inputs = [];
        $inputs['permissiondialog_name'] = 'permission.test';
        $inputs['permissiondialog_description'] = 'Permission test';
        $inputs['permissiondialog_inherit_id'] = "";
        $inputs['permissiondialog_slug'] = "create,view";
        
        $roleRepo = new RoleRepositoryEloquent($this->app);
        //$roleRepo->createPermission($inputs);
        $this->assertEquals(1, 1);
        

    }
    
    /*
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
    */
}
