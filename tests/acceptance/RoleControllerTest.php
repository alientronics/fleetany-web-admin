<?php

namespace Tests;

use Tests\AcceptanceTestCase;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\MessageBag;

class RoleControllerTest extends AcceptanceTestCase
{

    public $mockRepo;

    protected function setUp()
    {
        parent::setUp();
        $this->mockRole = \Mockery::mock('alias:Alientronics\CachedEloquent\Role');
        $this->mockPermission = \Mockery::mock('alias:Kodeine\Acl\Models\Eloquent\Permission');
        $this->mockRepo = \Mockery::mock('Alientronics\FleetanyWebAdmin\Repositories\RoleRepositoryEloquent');
        $this->app->instance('Alientronics\FleetanyWebAdmin\Repositories\RoleRepositoryEloquent', $this->mockRepo);
    }

    public function testIndex()
    {
        $roles = [ 0 => (object)['name' => 'entity roles' , 'description' => 'role description'] ];
        $this->mockRepo->shouldReceive('results')->once()->andReturn($roles);
        $this->get('/role')->see('entity roles');
    }
    
    public function testCreate()
    {
        $permissions = [ 0 => (object)['name' => 'permission dummy'] ];
        $this->mockPermission->shouldReceive('get')->once()->andReturn($permissions);
        $this->mockPermission->shouldReceive('lists')->once()->andReturnSelf();
        $this->mockPermission->shouldReceive('splice')->once()->andReturnNull();
        $this->get('/role/create')->see('permission dummy');
    }
    
    public function testStoreTrue()
    {
        $role = (object)[
            'id' => 1,
            'name' => 'entity roles',
            'description' => 'role description',
        ];

        $this->mockRepo->shouldReceive('validateRole')->once()->andReturnNull();
        $this->mockRepo->shouldReceive('create')->once()->andReturn($role);
        $this->mockRepo->shouldReceive('updateRolePermissions')->once()->andReturnNull();
        $this->post('/role', ['permissions' => '2'])
            ->assertRedirectedTo('role', ['message'=>'general.succefullcreate']);
    }

    public function testStoreFalse()
    {
        $this->mockRepo->shouldReceive('validateRole')->once()->andReturn("error message");
        $this->post('/role')->assertRedirectedTo('', ['message'=>'error message']);
    }

    public function testStoreException()
    {
        $messageBag = new MessageBag(['error_validation']);
        $this->mockRepo->shouldReceive('validateRole')->once()->andThrow(new ValidatorException($messageBag));
        $this->post('/role')->assertRedirectedTo('', ['errors'=> $messageBag]);
    }

    public function testEdit()
    {
        $permissions = [ 0 => (object)['name' => 'permission role'] ];
        $role = (object)[
            'id' => 1,
            'name' => 'entity roles',
            'description' => 'role description',
            'permissions' => $this->mockPermission
        ];

        $this->mockRepo->shouldReceive('find')->once()->andReturn($role);
        $this->mockPermission->shouldReceive('toArray')->once()->andReturn([]);
        $this->mockPermission->shouldReceive('get')->once()->andReturn($permissions);
        $this->mockPermission->shouldReceive('lists')->once()->andReturnSelf();
        $this->mockPermission->shouldReceive('splice')->once()->andReturnNull();
        $this->get('/role/{$role->id}/edit')->see('permission role');
    }

    public function testUpdateTrue()
    {
        $role = (object)[
            'id' => 1,
            'name' => 'entity roles',
            'description' => 'role description',
        ];

        $this->mockRepo->shouldReceive('find')->once()->with($role->id)->andReturn($role);
        $this->mockRepo->shouldReceive('validateRole')->once()->andReturnNull();
        $this->mockRepo->shouldReceive('update')->once()->andReturnNull();
        $this->mockRepo->shouldReceive('updateRolePermissions')->once()->andReturnNull();
        $this->put('/role/1', ['permissions' => '2'])
            ->assertRedirectedTo('role', ['message'=>'general.succefullupdate']);
    }

   
    public function testUpdateFalse()
    {
        $this->mockRepo->shouldReceive('find')->once()->with(1)->andReturnNull();
        $this->mockRepo->shouldReceive('validateRole')->once()->andReturn("error message");
        $this->put('/role/1')->assertRedirectedTo('', ['message'=>'error message']);
    }
    
    public function testUpdateException()
    {
        $messageBag = new MessageBag(['error_validation']);
        $this->mockRepo->shouldReceive('find')->once()->with(1)->andReturnNull();
        $this->mockRepo->shouldReceive('validateRole')->once()->andThrow(new ValidatorException($messageBag));
        $this->put('/role/1')->assertRedirectedTo('', ['errors'=> $messageBag]);
    }

    
    public function testDestroyTrue()
    {
        $role = (object)[
            'id' => 1,
            'name' => 'entity roles',
            'description' => 'role description',
        ];

        $this->mockRepo->shouldReceive('find')->twice()->with($role->id)->andReturn($role);
        $this->mockRepo->shouldReceive('delete')->twice()->andReturnNull();
        $this->delete('/role/1')->assertRedirectedTo('role', ['message'=>'general.deletedregister']);
        $this->get('/role/destroy/1')->assertRedirectedTo('role');
    }

    
    public function testDestroyFalse()
    {
        $this->mockRepo->shouldReceive('find')->twice()->with(1)->andReturnNull();
        $this->delete('/role/1')->assertRedirectedTo('role', ['message'=>'general.deletedregistererror']);
        $this->get('/role/destroy/1')->assertRedirectedTo('role');
    }

    public function testCreatePermissionTrue()
    {
        $this->mockRepo->shouldReceive('validatePermission')->once()->andReturnNull();
        $this->mockRepo->shouldReceive('createPermission')->once()->andReturnNull();
        $this->post('/permissions/create', ['permissiondialog_role_id' => 'role/create'])
            ->assertRedirectedTo('role/role/create/edit', ['message'=>'general.succefullcreate']);
    }

    public function testCreatePermissionFalse()
    {
        $this->mockRepo->shouldReceive('validatePermission')->once()->andReturn("error message");
        $this->post('/permissions/create')
            ->assertRedirectedTo('', ['message'=>"error message"]);
    }

    public function testCreatePermissionException()
    {
        $messageBag = new MessageBag(['error_validation']);
        $this->mockRepo->shouldReceive('validatePermission')->once()->andThrow(new ValidatorException($messageBag));
        $this->post('/permissions/create')->assertRedirectedTo('', ['errors'=> $messageBag]);
    }
}
