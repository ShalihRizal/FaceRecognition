<?php

namespace Modules\Face\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\PerceptualHash;

use Modules\Face\Repositories\FaceRepository;
use Modules\Users\Repositories\UsersRepository;
use App\Helpers\DataHelper;
use App\Helpers\LogHelper;
use DB;
use Validator;
use App\Services\FaceRecognitionService;

class FaceController extends Controller
{
    protected $faceRecognitionService;
    public function __construct()
    {
        $this->middleware('auth');

        $this->_faceRepository = new FaceRepository;
        $this->_usersRepository = new UsersRepository;
        $this->_logHelper           = new LogHelper;
        $this->module               = "Face";
        $this->faceRecognitionService = new FaceRecognitionService();
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        $faces = $this->_faceRepository->getAll();
        $users = $this->_usersRepository->getAll();

        return view('face::index', compact('faces', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }
        $users = $this->_usersRepository->getAll();
        $faces = $this->_faceRepository->getAll();

        return view('face::create', compact('users', 'faces'));
    }
    /**
     * Crop image to focus on face area
     */
    public function store(Request $request)
    {
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'face_image' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $imageData = $request->input('face_image');

            // Validasi base64 image
            if (!preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Format gambar tidak valid');
            }

            $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal decode gambar');
            }

            $imageName = 'face_cropped_' . time() . '_' . uniqid() . '.png';
            $facePath = 'uploads/images/' . $imageName;

            // Simpan gambar
            $saveResult = Storage::disk('public')->put($facePath, $imageData);

            if (!$saveResult) {
                throw new \Exception('Gagal menyimpan gambar wajah');
            }

            // Ambil data user yang sedang login
            $user = DB::table('sys_users')->where('user_id', Auth::id())->first();

            if (!$user) {
                DB::rollBack();
                Storage::disk('public')->delete($facePath);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'User tidak ditemukan');
            }

            $similarity = 0;
            $comparisonResult = "Tidak ada foto profil untuk dibandingkan";
            $methodUsed = 'python_face_recognition';

            // Pengecekan kemiripan dengan Python Face Recognition
            if ($user && $user->user_image) {
                $userImagePath = $this->getUserImagePath($user->user_image);

                if ($userImagePath && Storage::disk('public')->exists($userImagePath)) {

                    $fullUserImagePath = Storage::disk('public')->path($userImagePath);

                    // Gunakan Python Face Recognition API
                    $result = $this->faceRecognitionService->compareFacesViaApi(
                        $request->input('face_image'), // Kirim base64 asli
                        $fullUserImagePath
                    );

                    if ($result['success']) {
                        $similarity = $result['similarity'];
                        $comparisonResult = "Tingkat kemiripan wajah: " . $similarity . "% (Python Face Recognition)";

                        \Log::info('Python Face Recognition result', [
                            'user_id' => Auth::id(),
                            'similarity' => $similarity,
                            'face_distance' => $result['face_distance'] ?? 'N/A',
                            'faces_detected' => [
                                'captured' => $result['faces_detected_captured'] ?? 'N/A',
                                'profile' => $result['faces_detected_profile'] ?? 'N/A'
                            ]
                        ]);
                    } else {
                        throw new \Exception('Python Face Recognition error: ' . ($result['error'] ?? 'Unknown error'));
                    }

                    // Tolak jika similarity < 90%
                    if ($similarity < 60) {
                        DB::rollBack();
                        Storage::disk('public')->delete($facePath);
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Wajah tidak cocok! ' . $comparisonResult . '. Minimal 70%');
                    }
                } else {
                    $comparisonResult = "Peringatan: File foto profil tidak ditemukan";
                }
            } else {
                $comparisonResult = "Peringatan: User tidak memiliki foto profil untuk perbandingan";
            }

            // Simpan data face
            $faceData = DataHelper::_normalizeParams(array_merge($request->all(), [
                'face_user_id' => Auth::id(),
                'face_image' => $facePath,
                'similarity_score' => $similarity,
                'comparison_notes' => $comparisonResult,
                'comparison_method' => $methodUsed
            ]), true);

            $this->_faceRepository->insert($faceData);
            $this->_logHelper->store($this->module, Auth::id(), 'create', $comparisonResult);

            DB::commit();

            return redirect('face')->with('message', 'Data wajah berhasil ditambahkan. ' . $comparisonResult);
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($facePath) && Storage::disk('public')->exists($facePath)) {
                Storage::disk('public')->delete($facePath);
            }
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function getUserImagePath($userImage)
    {
        if (Storage::disk('public')->exists($userImage)) {
            return $userImage;
        }

        $possiblePaths = [
            'profile/' . $userImage,
            'uploads/profile/' . $userImage,
            'images/profile/' . $userImage,
        ];

        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }

        return $userImage;
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

        return view('face::show');
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

        return view('face::edit');
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
        dd($request->all());
        DB::beginTransaction();

        $this->_faceRepository->update(DataHelper::_normalizeParams($request->all(), false, true), $id);
        $this->_logHelper->store($this->module, $request->module_name, 'update');

        DB::commit();

        return redirect('face')->with('message', 'Modul berhasil diubah');
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
        $faces  = $this->_faceRepository->getById($id);

        if (!$detail) {
            return redirect('face');
        }

        DB::beginTransaction();

        $this->_faceRepository->delete($id);
        $this->_logHelper->store($this->module, $detail->face_user_id, 'delete');

        DB::commit();

        return redirect('face')->with('message', 'Face berhasil dihapus');
    }
    public function getdata($id)
    {

        $response   = array('status' => 0, 'result' => array());
        $getDetail  = $this->_faceRepository->getById($id);

        if ($getDetail) {
            $response['status'] = 1;
            $response['result'] = $getDetail;
        }

        return $response;
    }
}
