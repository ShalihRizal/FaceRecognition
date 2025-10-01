<?php

namespace Modules\UserGroup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

use Modules\UserGroup\Repositories\UserGroupRepository;
use App\Helpers\DataHelper;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\DB;
use Validator;

class UserGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->_usergroupRepository = new UserGroupRepository;
        $this->module               = "UserGroup";
        $this->_logHelper           = new LogHelper;
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

        $groups = $this->_usergroupRepository->getAll();

        return view('usergroup::index', compact('groups'));
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

        return view('usergroup::create');
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
            return redirect('usergroup')
                ->withErrors($validator)
                ->withInput();
        }
        DB::beginTransaction();

        $this->_usergroupRepository->insert(DataHelper::_normalizeParams($request->all()));
        $this->_logHelper->store($this->module, $request->group_name, 'create');
        DB::commit();


        return redirect('usergroup')->with('message', 'Grup berhasil ditambahkan');
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

        return view('usergroup::show');
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

        return view('usergroup::edit');
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
            return redirect('usergroup')
                ->withErrors($validator)
                ->withInput();
        }
        DB::beginTransaction();
        $this->_usergroupRepository->update(DataHelper::_normalizeParams($request->all()), $id);
        $this->_logHelper->store($this->module, $request->group_name, 'update');
        DB::commit();

        return redirect('usergroup')->with('message', 'Grup pengguna berhasil diubah');
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


        $response   = array('status' => 0, 'result' => array());

        // Check detail to db
        $detail  = $this->_usergroupRepository->getById($id);

        if (!$detail) {
            // return redirect('usergroup');
            $response['status'] = 0;
            $response['message'] = 'Data tidak ditemukan!';
        } elseif ($detail->group_id == 1) {
            // return redirect('usergroup');
            $response['status'] = 0;
            $response['message'] = 'Data tidak dapat dihapus!';
        } else {
            DB::beginTransaction();
            $this->_usergroupRepository->delete($id);
            $this->_logHelper->store($this->module, $detail->group_name, 'delete');
            DB::commit();

            $response['status'] = 1;
            $response['message'] = 'Data berhasil dihapus!';
        }

        return $response;
    }

    /**
     * Get data the specified resource in storage.
     * @param int $id
     * @return Response
     */
    public function getdata($id)
    {

        $response   = array('status' => 0, 'result' => array());
        $getDetail  = $this->_usergroupRepository->getById($id);

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
                'group_name' => 'required|unique:sys_user_groups',
            ];
        } else {
            return [
                'group_name' => 'required|unique:sys_user_groups,group_name,' . $id . ',group_id',
            ];
        }
    }
}
