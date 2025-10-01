<?php

namespace Modules\SysModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

use Modules\SysModule\Repositories\SysModuleRepository;
use App\Helpers\DataHelper;
use App\Helpers\LogHelper;
use DB;
use Validator;

class SysModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->_sysmoduleRepository = new SysModuleRepository;
        $this->_logHelper           = new LogHelper;
        $this->module               = "SysModule";
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

        $modules = $this->_sysmoduleRepository->getAll();

        return view('sysmodule::index', compact('modules'));
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

        return view('sysmodule::create');
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

        $validator = Validator::make($request->all(), $this->_validationRules(''));

        if ($validator->fails()) {
            return redirect('sysmodule')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        $this->_sysmoduleRepository->insert(DataHelper::_normalizeParams($request->all(), true));
        $this->_logHelper->store($this->module, $request->module_name, 'create');
        DB::commit();

        return redirect('sysmodule')->with('message', 'Modul berhasil ditambahkan');
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

        return view('sysmodule::show');
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

        return view('sysmodule::edit');
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

        $validator = Validator::make($request->all(), $this->_validationRules($id));

        if ($validator->fails()) {
            return redirect('sysmodule')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        $this->_sysmoduleRepository->update(DataHelper::_normalizeParams($request->all(), false, true), $id);
        $this->_logHelper->store($this->module, $request->module_name, 'update');

        DB::commit();

        return redirect('sysmodule')->with('message', 'Modul berhasil diubah');
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
        $detail  = $this->_sysmoduleRepository->getById($id);

        if (!$detail) {
            return redirect('sysmodule');
        }

        DB::beginTransaction();

        $this->_sysmoduleRepository->delete($id);
        $this->_logHelper->store($this->module, $detail->module_name, 'delete');

        DB::commit();

        return redirect('sysmodule')->with('message', 'Modul berhasil dihapus');
    }

    /**
     * Get data the specified resource in storage.
     * @param int $id
     * @return Response
     */
    public function getdata($id)
    {

        $response   = array('status' => 0, 'result' => array());
        $getDetail  = $this->_sysmoduleRepository->getById($id);

        if ($getDetail) {
            $response['status'] = 1;
            $response['result'] = $getDetail;
        }

        return $response;
    }

    private function _validationRules($id = '')
    {
        if ($id == '') {
            return [
                'module_name' => 'required|unique:sys_modules',
            ];
        } else {
            return [
                'module_name' => 'required|unique:sys_modules,module_name,' . $id . ',module_id',
            ];
        }
    }
}
