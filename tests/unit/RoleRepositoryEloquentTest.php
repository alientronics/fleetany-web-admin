<?php

namespace Tests\Unit;

use Tests\UnitTestCase;
use Alientronics\FleetanyWebAdmin\Repositories\RoleRepositoryEloquent;
use Illuminate\Support\Facades\Lang;

class RoleRepositoryEloquentTest extends UnitTestCase
{
    
    public $roleRepo;
    public $mockPermission;
    public $mockRole;

    public function setUp()
    {
        parent::setUp();
        
        $this->mockRole = \Mockery::mock('alias:Alientronics\CachedEloquent\Role');
        $this->mockPermission = \Mockery::mock('alias:Kodeine\Acl\Models\Eloquent\Permission');
        $this->roleRepo = \Mockery::mock(RoleRepositoryEloquent::class)->makePartial();
    }

    public function testResults()
    {
        $setFilters = [
            'name' => 'permission.test',
            'description' => 'Permission test',
            'sort' => 'name',
            'order' => 'name',
            'paginate' => '1',
        ];

        $this->mockRole->shouldReceive('select')->once()->andReturnSelf();
        $this->mockRole->shouldReceive('where')->twice()->andReturnSelf();
        $this->mockRole->shouldReceive('orderBy')->once()->andReturnSelf();
        $this->mockRole->shouldReceive('paginate')->once()->andReturnSelf();

        $this->roleRepo->results($setFilters);
    }

    public function testValidateRole()
    {
        $inputs = [];
        $inputs['name'] = 'permission.test';
        $inputs['description'] = 'Permission test';
        $inputs['slug'] = "create,view";

        $objRole = (object)[
            'name' => $inputs['name'],
            'description' => $inputs['description'],
            'slug' => $inputs['slug']
        ];
        $permission_id = 2;

        $this->mockRole->shouldReceive('where')->twice()->andReturnSelf();
        $this->mockRole->shouldReceive('first')->once()->andReturn($objRole);
        $this->roleRepo->validateRole($inputs, $permission_id);
    }
    
    public function testValidateRoleDescriptionExists()
    {
        $inputs = [];
        $inputs['name'] = 'permission.test';
        $inputs['description'] = 'Permission test';
        $inputs['slug'] = "create,view";
    
        $objRole = (object)[
            'name' => $inputs['name']."copy",
            'description' => $inputs['description'],
            'slug' => $inputs['slug']
        ];
        $permission_id = 2;
    
        $this->mockRole->shouldReceive('where')->twice()->andReturnSelf();
        $this->mockRole->shouldReceive('first')->once()->andReturn($objRole);
        $this->assertEquals(Lang::get('admin.slugexists'), $this->roleRepo->validateRole($inputs, $permission_id));
    }
    
    public function testValidateRoleSlugExists()
    {
        $inputs = [];
        $inputs['name'] = 'permission.test';
        $inputs['description'] = 'Permission test';
        $inputs['slug'] = "create,view";
    
        $objRole = (object)[
            'name' => $inputs['name']."copy",
            'description' => $inputs['description']."copy",
            'slug' => $inputs['slug']
        ];
        $permission_id = 2;
    
        $this->mockRole->shouldReceive('where')->twice()->andReturnSelf();
        $this->mockRole->shouldReceive('first')->once()->andReturn($objRole);
        $this->assertEquals(Lang::get('admin.slugexists'), $this->roleRepo->validateRole($inputs, $permission_id));
    }
    
    public function testValidatePermission()
    {
        $inputs = [];
        $inputs['name'] = 'permission.test';
        $inputs['description'] = 'Permission test';

        $objPermission = (object)[
            'name' => $inputs['name'],
            'description' => $inputs['description']
        ];
        $permission_id = 2;

        $this->mockPermission->shouldReceive('where')->twice()->andReturnSelf();
        $this->mockPermission->shouldReceive('first')->once()->andReturn($objPermission);
        $this->roleRepo->validatePermission($inputs, $permission_id);
    }

    public function testUpdateRolePermissions()
    {
        $role_id = 1;
        $permissions = 2;

        $this->mockRole->shouldReceive('find')->once()->andReturnSelf();
        $this->mockRole->shouldReceive('syncPermissions')->once();
        $this->roleRepo->updateRolePermissions($role_id, $permissions);
    }

    public function testCreatePermission()
    {
        $inputs = [];
        $inputs['permissiondialog_name'] = 'permission.test';
        $inputs['permissiondialog_description'] = 'Permission test';
        $inputs['permissiondialog_inherit_id'] = "";
        $inputs['permissiondialog_slug'] = "create,view";

        $this->mockPermission->shouldReceive('create')->once();
        $this->roleRepo->createPermission($inputs);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
