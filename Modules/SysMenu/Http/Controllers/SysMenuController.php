<?php

namespace Modules\SysMenu\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

use Modules\SysMenu\Repositories\SysMenuRepository;
use Modules\SysModule\Repositories\SysModuleRepository;
use App\Helpers\DataHelper;
use App\Helpers\LogHelper;
use DB;

class SysMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->_sysmenuRepository   = new SysMenuRepository;
        $this->_sysmoduleRepository = new SysModuleRepository;
        $this->module = "SysMenu";

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

        $menus      = $this->_sysmenuRepository->getAll();
        $parents    = $this->_sysmenuRepository->getAllByParams(['menu_is_sub' => '0']);
        $modules    = $this->_sysmoduleRepository->getAll();

        return view('sysmenu::index', compact('menus', 'modules', 'parents'));
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

        return view('sysmenu::create');
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

        $this->_sysmenuRepository->insert(DataHelper::_normalizeParams($request->all(), true));

        $this->_logHelper->store($this->module, $request->menu_name, 'create');

        DB::commit();

        return redirect('sysmenu')->with('message', 'Menu berhasil ditambahkan');

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

        return view('sysmenu::show');
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

        // $modules    = $this->_sysmoduleRepository->getAll();

        return view('sysmenu::edit');
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

        $this->_sysmenuRepository->update(DataHelper::_normalizeParams($request->all(), false, true), $id);
        $this->_logHelper->store($this->module, $request->menu_name, 'update');

        DB::commit();

        return redirect('sysmenu')->with('message', 'Menu berhasil diubah');
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
         $detail  = $this->_sysmenuRepository->getById($id);

         if (!$detail) {
             return redirect('sysmenu');
         }
        DB::beginTransaction();
        $this->_sysmenuRepository->delete($id);
        $this->_logHelper->store($this->module, $detail->menu_name, 'delete');
        DB::commit();


        return redirect('sysmenu')->with('message', 'Menu berhasil dihapus');
    }

    /**
     * Get data the specified resource in storage.
     * @param int $id
     * @return Response
     */
    public function getdata($id){

        $response   = array('status' => 0, 'result' => array());
        $getDetail  = $this->_sysmenuRepository->getById($id);

        if ($getDetail) {
            $response['status'] = 1;
            $response['result'] = $getDetail;
        }

        return $response;

    }

}
