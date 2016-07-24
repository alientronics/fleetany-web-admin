<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\RoleRepositoryEloquent;
use Lang;
use Log;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Auth;
use Alientronics\CachedEloquent\Role;
use Kodeine\Acl\Models\Eloquent\Permission;

class RoleController extends Controller
{

    protected $roleRepo;
    protected $inputs;
    
    protected $fields = [
        'id',
        'name',
        'description'
    ];
    
    public function __construct(RoleRepositoryEloquent $roleRepo)
    {
        parent::__construct();

        $this->middleware('admin');
        $this->roleRepo = $roleRepo;
        
        $this->inputs = $this->request->all();
        $this->inputs['company_id'] = Auth::user()['company_id'];
    }

    public function index()
    {
        $filters = $this->helper->getFilters($this->request->all(), $this->fields, $this->request);

        $roles = $this->roleRepo->results($filters);

        return view("role.index", compact('roles', 'filters'));
    }
    
    public function create()
    {
        $role = new Role();
        $role_permissions = [];

        $permissions = Permission::get();
    
        $permissiondialog = [];
        $permissiondialog['permissions'] = Permission::lists('name', 'id');
        $permissiondialog['permissions']->splice(0, 0, ["" => ""]);
        $permissiondialog['role_id'] = "";
        
        return view("role.edit", compact('role', 'role_permissions', 'permissions', 'permissiondialog'));
    }

    public function store()
    {
        try {
            $this->roleRepo->validator();
            $inputs = $this->request->all();
            $validateRole = $this->roleRepo->validateRole($inputs);
            if (!empty($validateRole)) {
                return $this->redirect->back()->with('message', $validateRole);
            }
            
            $role = $this->roleRepo->create($inputs);
            $this->roleRepo->updateRolePermissions($role->id, $inputs['permissions']);
            
            return $this->redirect->to('role')->with('message', Lang::get(
                'general.succefullcreate',
                ['table'=> Lang::get('admin.Role')]
            ));
        } catch (ValidatorException $e) {
            return $this->redirect->back()->withInput()
                   ->with('errors', $e->getMessageBag());
        }
    }
    
    public function edit($idRole)
    {
        $role = $this->roleRepo->find($idRole);

        $role_permissions = [];
        foreach ($role->permissions->toArray() as $rolePerm) {
            $role_permissions[] = $rolePerm['id'];
        }
        
        $permissions = Permission::get();
    
        $permissiondialog = [];
        $permissiondialog['permissions'] = Permission::lists('name', 'id');
        $permissiondialog['permissions']->splice(0, 0, ["" => ""]);
        $permissiondialog['role_id'] = $role->id;
        
        return view("role.edit", compact(
            'role',
            'role_permissions',
            'permissions',
            'permissiondialog'
        ));
    }
    
    public function update($idRole)
    {
        try {
            $role = $this->roleRepo->find($idRole);
            $this->roleRepo->validator();
            $inputs = $this->request->all();
            $validateRole = $this->roleRepo->validateRole($inputs, $idRole);
            if (!empty($validateRole)) {
                return $this->redirect->back()->with('message', $validateRole);
            }
            $this->roleRepo->update($inputs, $idRole);
            $this->roleRepo->updateRolePermissions($role->id, $inputs['permissions']);
        
            return $this->redirect->to('role')->with('message', Lang::get(
                'general.succefullupdate',
                ['table'=> Lang::get('admin.Role')]
            ));
        } catch (ValidatorException $e) {
            return $this->redirect->back()->withInput()
                   ->with('errors', $e->getMessageBag());
        }
    }
    
    public function destroy($idRole)
    {
        $role = $this->roleRepo->find($idRole);
        if ($role) {
            Log::info('Delete field: '.$idRole);
            $this->roleRepo->delete($idRole);
            return $this->redirect->to('role')->with('message', Lang::get("general.deletedregister"));
        } else {
            return $this->redirect->to('role')->with('message', Lang::get("general.deletedregistererror"));
        }
    }

    public function createPermission()
    {
        try {
            $inputs = $this->request->all();
            $validatePermission = $this->roleRepo->validatePermission($inputs);
            if (!empty($validatePermission)) {
                return $this->redirect->back()->with('message', $validatePermission);
            }
            
            $this->roleRepo->createPermission($inputs);
            
            $urlBack = empty($inputs['permissiondialog_role_id']) ? 'role/create' :
                                    'role/'.$inputs['permissiondialog_role_id'].'/edit';
            
            return $this->redirect->to($urlBack)->with('message', Lang::get(
                'general.succefullcreate',
                ['table'=> Lang::get('admin.Permission')]
            ));
        } catch (ValidatorException $e) {
            return $this->redirect->back()->withInput()
                   ->with('errors', $e->getMessageBag());
        }
    }
}
