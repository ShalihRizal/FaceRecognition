<?php

namespace Modules\SysTask\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

use Modules\SysTask\Repositories\SysTaskRepository;
use Modules\SysTask\Repositories\TaskDataRepository;
use Modules\SysModule\Repositories\SysModuleRepository;
use App\Helpers\DataHelper;
use App\Helpers\LogHelper;
use DB;

class SysTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->_systaskRepository   = new SysTaskRepository;
        $this->_taskdataRepository   = new TaskDataRepository;
        $this->_sysmoduleRepository = new SysModuleRepository;
        $this->_logHelper           = new LogHelper;
        $this->module               = "SysTask";
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

        $tasks      = $this->_systaskRepository->getAll();
        $tasks_data      = $this->_taskdataRepository->getAll();
        $modules    = $this->_sysmoduleRepository->getAll();

        return view('systask::index', compact('tasks', 'modules', 'tasks_data'));
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

        return view('systask::create');
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

        try {
            $exists = [];
            $inserted = [];

            foreach ($request->task_data_id as $taskDataId) {
                $existsCheck = DB::table('sys_tasks')
                    ->where('module_id', $request->module_id)
                    ->where('task_data_id', $taskDataId)
                    ->exists();

                    $modules    = $this->_sysmoduleRepository->getById($request->module_id);
                    $tasks_data      = $this->_taskdataRepository->getById($taskDataId);
                if ($existsCheck) {
                    $exists[] = [
                        'module_id'    => $modules->module_name,
                        'task_data_id' => $tasks_data->task_data_name,
                    ];
                } else {
                    $data = [
                        'module_id'    => $request->module_id,
                        'task_data_id' => $taskDataId,
                    ];
                    
                    $getdata = [
                        'module_id'    => $modules->module_name,
                        'task_data_id' => $tasks_data->task_data_name,
                    ];

                    $this->_systaskRepository->insert(DataHelper::_normalizeParams($data, true));
                    $inserted[] = $getdata;
                }
            }

            DB::commit();

            if (!empty($exists)) {
                return response()->json([
                    'status'  => 'warning',
                    'message' => 'Beberapa data sudah ada.',
                    'exists'  => $exists,
                    'inserted'=> $inserted
                ]);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Task berhasil ditambahkan.',
                'inserted'=> $inserted
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menambahkan task: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function storeDataTask(Request $request)
    {
        // Authorize
        // if (Gate::denies(__FUNCTION__, $this->module)) {
        //     return redirect('unauthorize');
        // }

        DB::beginTransaction();
        try {
            $existsCheck = DB::table('task_data')
                ->whereRaw('LOWER(task_data_name) = ?', [strtolower($request->task_data_name)])
                ->exists();
            
            if ($existsCheck) {
                return response()->json([
                    'status'  => 'warning',
                    'message' => 'Data sudah ada.',
                ]);
            }else{
                $data = $this->_taskdataRepository->insert(DataHelper::_normalizeParams($request->all(), true));
                
                $this->_logHelper->store($this->module, $request->task_data_name, 'create');
        
                DB::commit();

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Task berhasil ditambahkan',
                ]);
        
                return redirect('systask')->with('message', 'Task berhasil ditambahkan');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menambahkan task: ' . $e->getMessage(),
            ], 500);
        }
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

        return view('systask::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        return view('systask::edit');
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

        DB::beginTransaction();

        $this->_systaskRepository->update(DataHelper::_normalizeParams($request->all(), false, true), $id);
        $this->_logHelper->store($this->module, $request->task_name, 'update');

        DB::commit();

        return redirect('systask')->with('message', 'Task berhasil diubah');
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
        $detail  = $this->_systaskRepository->getById($id);

        if (!$detail) { 
            return redirect('systask');
        }

        DB::beginTransaction();

        $this->_systaskRepository->delete($id);
        $this->_logHelper->store($this->module, $detail->task_data_name, 'delete');

        DB::commit();

        return redirect('systask')->with('message', 'Task berhasil dihapus');
    }

    /**
     * Get data the specified resource in storage.
     * @param int $id
     * @return Response
     */
    public function getdata($id)
    {

        $response   = array('status' => 0, 'result' => array());
        $getDetail  = $this->_systaskRepository->getById($id);

        if ($getDetail) {
            $response['status'] = 1;
            $response['result'] = $getDetail;
        }

        return $response;
    }
}
