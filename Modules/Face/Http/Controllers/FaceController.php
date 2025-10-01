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
use App\Services\EnhancedFaceRecognitionService;
use DB;
use Validator;

class FaceController extends Controller
{
    protected $faceRecognitionService;
    protected $_faceRepository;
    protected $_usersRepository;
    protected $_logHelper;
    protected $module;

    public function __construct()
    {
        $this->middleware('auth');

        $this->_faceRepository = new FaceRepository;
        $this->_usersRepository = new UsersRepository;
        $this->_logHelper = new LogHelper;
        $this->module = "Face";

        $this->faceRecognitionService = new EnhancedFaceRecognitionService();
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
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
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

        // CEK APAKAH USER MEMILIKI FOTO PROFIL
        $user = DB::table('sys_users')->where('user_id', Auth::id())->first();

        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'User tidak ditemukan');
        }

        // Validasi apakah user memiliki foto profil
        $userImagesArray = $user->user_image ? json_decode($user->user_image, true) : [];
        $totalImages = is_array($userImagesArray) ? count($userImagesArray) : 0;

        if ($totalImages === 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tidak dapat menyimpan data wajah! Anda belum memiliki foto profil. Silakan perbarui foto profil terlebih dahulu.');
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

            $similarity = 0;
            $comparisonResult = "Tidak ada foto profil untuk dibandingkan";
            $methodUsed = 'enhanced_python_face_recognition_multiple';

            // Pengecekan kemiripan dengan Multiple Python Face Recognition
            if ($user && $user->user_image) {
                // Decode JSON array of user images
                $userImagesArray = json_decode($user->user_image, true);

                if (is_array($userImagesArray) && !empty($userImagesArray)) {
                    \Log::info('Starting multiple image comparison', [
                        'user_id' => Auth::id(),
                        'total_profile_images' => count($userImagesArray),
                        'profile_images' => $userImagesArray
                    ]);

                    // Gunakan enhanced service dengan multiple images
                    $result = $this->faceRecognitionService->compareFacesWithFallback(
                        $request->input('face_image'), // Kirim base64 asli
                        $userImagesArray,
                        Auth::id()
                    );

                    if ($result['success']) {
                        $similarity = $result['similarity'];
                        $averageSimilarity = $result['statistics']['average_similarity'] ?? $similarity;
                        $bestMatchImage = $result['best_match_image'] ?? 'N/A';
                        $successfulComparisons = $result['statistics']['successful_comparisons'] ?? 1;
                        $totalComparisons = $result['statistics']['total_comparisons'] ?? count($userImagesArray);
                        $validationPassed = $result['validation_passed'] ?? false;
                        $validationScore = $result['validation_score'] ?? 0;

                        $comparisonResult = "Tingkat kemiripan wajah: " . $similarity . "% " .
                            "(Rata-rata: " . $averageSimilarity . "%) " .
                            "| Foto terbaik: " . $bestMatchImage . " " .
                            "| Berhasil: " . $successfulComparisons . "/" . $totalComparisons . " foto" .
                            " | Validasi: " . ($validationPassed ? "Lulus ({$validationScore}%)" : "Gagal ({$validationScore}%)");

                        // ADAPTIVE THRESHOLD berdasarkan validasi
                        $baseThreshold = 70;
                        $adaptiveThreshold = $validationPassed ? $baseThreshold : $baseThreshold + 10;

                        // Jika validasi gagal, butuh similarity yang lebih tinggi
                        if (!$validationPassed && $similarity < 80) {
                            DB::rollBack();
                            Storage::disk('public')->delete($facePath);

                            $errorMessage = 'Wajah tidak cocok! ' . $comparisonResult .
                                '. Sistem membutuhkan kemiripan minimal ' . $adaptiveThreshold .
                                '% karena validasi gagal. Silakan coba lagi dengan pencahayaan yang lebih baik.';

                            return redirect()->back()
                                ->withInput()
                                ->with('error', $errorMessage);
                        }

                        // Threshold normal untuk hasil yang tervalidasi
                        if ($similarity < $baseThreshold) {
                            DB::rollBack();
                            Storage::disk('public')->delete($facePath);

                            $errorMessage = 'Wajah tidak cocok! ' . $comparisonResult .
                                '. Minimal kemiripan ' . $baseThreshold . '%';

                            return redirect()->back()
                                ->withInput()
                                ->with('error', $errorMessage);
                        }
                    } else {
                        throw new \Exception('Enhanced Multiple Face Recognition error: ' . ($result['error'] ?? 'Wajah Berbeda'));
                    }
                } else {
                    $comparisonResult = "Peringatan: Format foto profil tidak valid atau kosong";
                    \Log::warning('Invalid profile images format', [
                        'user_id' => Auth::id(),
                        'user_image' => $user->user_image
                    ]);
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
            \Log::error('Face storage error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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
            'uploads/images/' . $userImage,
            'storage/profile/' . $userImage,
        ];

        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }

        // Coba dengan path lengkap
        $fullPath = storage_path('app/public/' . $userImage);
        if (file_exists($fullPath)) {
            return $userImage;
        }

        \Log::warning('User image not found in any path', [
            'user_image' => $userImage,
            'searched_paths' => $possiblePaths
        ]);

        return $userImage;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
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
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

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
        if (Gate::denies(__FUNCTION__, $this->module)) {
            return redirect('unauthorize');
        }

        // Check detail to db
        $detail = $this->_faceRepository->getById($id);

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

    /**
     * Health check untuk Python API
     */
    public function healthCheck()
    {
        try {
            $health = $this->faceRecognitionService->healthCheck();
            return response()->json($health);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
