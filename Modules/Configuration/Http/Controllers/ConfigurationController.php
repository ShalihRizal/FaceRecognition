<?php

namespace Modules\Configuration\Http\Controllers;

use App\Helpers\DataHelper;
use App\Helpers\LogHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Modules\Configuration\Repositories\ConfigurationRepository;

class ConfigurationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->_configurationRepository = new ConfigurationRepository;
        $this->_logHelper           = new LogHelper;
        $this->module               = "Configuration";
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        $configurations = $this->_configurationRepository->getAll();

        return view('configuration::index', compact('configurations'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }
        return view('configuration::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        $validator = Validator::make($request->all(), $this->_validationRules(''));

        if ($validator->fails()) {
            return redirect('configuration')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        $this->_configurationRepository->insert(DataHelper::_normalizeParams($request->all(), true));
        $this->_logHelper->store($this->module, $request->key, 'create');
        DB::commit();

        return redirect('configuration')->with('message', 'Data konfigurasi berhasil ditambahkan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        return view('configuration::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        return view('configuration::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        $validator = Validator::make($request->all(), $this->_validationRules($id));

        if ($validator->fails()) {
            return redirect('configuration')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        $this->_configurationRepository->update(DataHelper::_normalizeParams($request->all(), false, true), $id);
        $this->_logHelper->store($this->module, $request->key, 'update');

        DB::commit();


        return redirect('configuration')->with('message', 'Data konfigurasi berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        // Authorize
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }
        // Check detail to db
        $detail  = $this->_configurationRepository->getById($id);

        if (!$detail) {
            return redirect('configuration');
        }

        DB::beginTransaction();

        $this->_configurationRepository->delete($id);
        $this->_logHelper->store($this->module, $detail->key, 'delete');

        DB::commit();

        return redirect('configuration')->with('message', 'Data Konfigurasi berhasil dihapus');
    }

    /**
     * Get data the specified resource in storage.
     * @param int $id
     * @return Response
     */
    public function getdata($id)
    {
        $getDetail  = $this->_configurationRepository->getById($id);

        if ($getDetail) {
            return DataHelper::_successResponse($getDetail, 'Data berhasil ditemukan');
        }
        return DataHelper::_errorResponse(null, 'Data tidak ditemukan');
    }

    private function _validationRules($id = '')
    {
        if ($id == '') {
            return [
                'key' => 'required',
            ];
        } else {
            return [
                'key' => 'required',
            ];
        }
    }
}
