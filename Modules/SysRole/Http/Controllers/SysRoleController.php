<?php

namespace Modules\SysRole\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use Modules\SysTask\Repositories\SysTaskRepository;
use Modules\SysModule\Repositories\SysModuleRepository;
use Modules\SysRole\Repositories\SysRoleRepository;
use Modules\UserGroup\Repositories\UserGroupRepository;
use Modules\SysTask\Repositories\TaskDataRepository;
use App\Helpers\DataHelper;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\Auth;

class SysRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->_sysroleRepository   = new SysRoleRepository;
        $this->_usergroupRepository = new UserGroupRepository;
        $this->_taskdataRepository   = new TaskDataRepository;
        $this->module               = "SysRole";
        $this->_logHelper       = new LogHelper;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        $roles      = $this->_sysroleRepository->getAll();
        $groups     = $this->_usergroupRepository->getAll();
        
        return view('sysrole::index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        return view('sysrole::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }
        DB::beginTransaction();
        $this->_sysroleRepository->insert(DataHelper::_normalizeParams($request->all(), true));
        $this->_logHelper->store($this->module, $request->task_id, 'create');
        DB::commit();

        return redirect('sysrole')->with('message', 'Role berhasil ditambahkan');

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        return view('sysrole::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {

        // // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        $modules     = $this->_sysroleRepository->getModuleTask();
        $tasks_data      = $this->_taskdataRepository->getAll();
        $roles       = $this->_sysroleRepository->getAllByParams(['group_id' => $id]);
        $roleTasks   = array();

        // echo json_encode($roles);
        // exit;

        if ($roles) {
            
            foreach ($roles as $role) {
                $roleTasks[] = $role->task_id;
            }

        }

        return view('sysrole::edit', compact('modules', 'roleTasks', 'id', 'tasks_data'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        $tasks = $request->input('task');

        try {
            
            DB::beginTransaction();

            for ($i = 0; $i < sizeof($tasks); $i++) { 
                
                // Check role
                $checkRole = $this->_sysroleRepository->getByParams(['task_id' => $tasks[$i], 'group_id' => $id]);

                if (!$checkRole) {
                    
                    $value = array(
                                'group_id'  => $id,
                                'task_id'   => $tasks[$i],
                                'created_at' => date('Y-m-d H:i:s'),
                                'created_by' => Auth::user()->user_id,
                            );

                    $this->_sysroleRepository->insert($value);

                }

            }

            $this->_sysroleRepository->deleteByTask($id, $tasks);

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }

        $this->_logHelper->store($this->module, $request->task_id, 'update');

        return redirect('sysrole')->with('message', 'Role berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }
        // Check detail to db
        $detail  = $this->_sysroleRepository->getById($id);

        if (!$detail) {
            return redirect('sysrole');
        }
        DB::beginTransaction();
        
        $this->_sysroleRepository->delete($id);

        $this->_logHelper->store($this->module, $detail->task_id, 'delete');

        DB::commit();

        return redirect('sysrole')->with('message', 'Role berhasil dihapus');
    }

    /**
     * Get data the specified resource in storage.
     * @param int $id
     * @return Response
     */
    public function getdata($id){

        $response   = array('status' => 0, 'result' => array()); 
        $getDetail  = $this->_sysroleRepository->getById($id);

        if ($getDetail) {
            $response['status'] = 1;
            $response['result'] = $getDetail;
        }

        return $response;

    }

}
