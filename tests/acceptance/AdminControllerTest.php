<?php

namespace Tests;

use Tests\AcceptanceTestCase;

class AdminControllerTest extends AcceptanceTestCase
{

    private function setEloquentMock($method, $return)
    {
        $mockRepo = \Mockery::mock('Alientronics\FleetanyWebAdmin\Repositories\RoleRepositoryEloquent');
        $mockRepo->shouldReceive($method)->andReturn($return);

        $this->app->instance('Alientronics\FleetanyWebAdmin\Repositories\RoleRepositoryEloquent', $mockRepo);
    }

    public function testIndex()
    {
    	$roles = [ 0 => (object)['name' => 'entity roles' , 'description' => 'role description'] ];
        $this->setEloquentMock('results', $roles);
        $this->get('/role')->see('entity roles');
    }
    /*
    public function testCreate()
    {
        $this->get('/role/create')->see('vehicle');
    }

    public function testEdit()
    {
        $this->setEloquentMock('getKey', (object)['key'=>'entity key']);
        $this->get('/role/1/edit')->see('entity key');
    }

    public function testUpdateTrue()
    {
        $this->setEloquentMock('updateKey', true);
        $this->put('/role/1')->assertRedirectedTo('role', ['message'=>'general.succefullupdate']);
    }

    public function testUpdateFalse()
    {
        $this->setEloquentMock('updateKey', false);
        $this->put('/role/1')->assertRedirectedTo('/');
    }

    public function testStoreTrue()
    {
        $this->setEloquentMock('createKey', true);
        $this->post('/role')->assertRedirectedTo('role', ['message'=>'general.succefullcreate']);
    }

    public function testStoreFalse()
    {
        $this->setEloquentMock('createKey', false);
        $this->post('/role')->assertRedirectedTo('/');
    }

    public function testDestroyTrue()
    {
        $this->setEloquentMock('deleteKey', true);
        $this->delete('/role/1')->assertRedirectedTo('role', ['message'=>'general.deletedregister']);
    }

    public function testDestroyFalse()
    {
        $this->setEloquentMock('deleteKey', false);
        $this->delete('/role/1')->assertRedirectedTo('role', ['message'=>'general.deletedregistererror']);
    }

    public function testDestroy()
    {
        $this->setEloquentMock('deleteKey', true);
        $this->get('/role/destroy/1')->assertRedirectedTo('role');
    }
    */
}
