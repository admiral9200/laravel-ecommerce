<?php

namespace AvoRed\Ecommerce\Http\Controllers;

use AvoRed\Ecommerce\DataGrid\Role;
use AvoRed\Ecommerce\Models\Database\Role as Model;
use AvoRed\Ecommerce\Http\Requests\RoleRequst;
use AvoRed\Ecommerce\Models\Database\Permission;
use AvoRed\Framework\DataGrid\Facade as DataGrid;

class RoleController extends Controller
{
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roleGrid = new Role(Model::query());

        return view('avored-ecommerce::role.index')->with('dataGrid', $roleGrid->dataGrid);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('avored-ecommerce::role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \AvoRed\Ecommerce\Http\Requests\RoleRequst $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequst $request)
    {
        try {
            $role = Model::create($request->all());
            $this->_saveRolePermissions($request, $role);
        } catch (\Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        return redirect()->route('admin.role.index')->with('notificationText', ' New Role has been Created Successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Model::findorfail($id);

        return view('avored-ecommerce::role.edit')
            ->with('model', $role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \AvoRed\Ecommerce\Http\Requests\RoleRequst $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequst $request, $id)
    {
        try {
            $role = Model::findorfail($id);
            $role->update($request->all());
            $this->_saveRolePermissions($request, $role);
        } catch (\Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        return redirect()->route('admin.role.index')->with('notificationText', ' Role Updates Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Model::destroy($id);
        return redirect()->route('admin.role.index')->with('notificationText', ' Role Destroy Successfully!');
    }

    private function _saveRolePermissions($request, $role)
    {
        $permissionIds = [];

        if (count($request->get('permissions')) > 0) {
          
            foreach ($request->get('permissions') as $key => $value) {
              
                if ($value != 1) {
                    continue;
                }
                $permissions = explode(',', $key);
                foreach ($permissions as $permissionName) {
                    if (null === ($permissionModel = Permission::getPermissionByName($permissionName))) {
                        $permissionModel = Permission::create(['name' => $permissionName]);
                    }
                    $permissionIds[] = $permissionModel->id;
                }
            }
        }
        $ids = array_unique($permissionIds);
        $role->permissions()->sync($ids);

        return $this;
    }
}
