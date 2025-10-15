<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiple Face Recognition</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Camera Responsive Styles */
        .camera-preview {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin: 0 auto;
            max-width: 100%;
        }

        .camera-wrapper {
            position: relative;
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
        }

        .camera-video {
            width: 100%;
            height: auto;
            max-width: 100%;
            border-radius: 8px;
            background: #000;
            transform: scaleX(-1);
            /* Mirror effect untuk natural view */
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .stat-item {
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .stat-value {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
        }

        .text-purple {
            color: #6f42c1 !important;
        }

        /* Valid Users List */
        .valid-users-list {
            max-height: 120px;
            overflow-y: auto;
            background: #f8f9fa;
            border-radius: 6px;
            padding: 10px;
            font-size: 12px;
        }

        .valid-user-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .valid-user-item:last-child {
            border-bottom: none;
        }

        .user-name {
            font-weight: 600;
            flex: 1;
        }

        .user-similarity {
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            background: #28a745;
            color: white;
        }

        /* Users List */
        .recognized-users-container {
            max-height: 600px;
            overflow-y: auto;
            padding: 15px;
        }

        .user-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            background: white;
            position: relative;
        }

        .user-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .user-card.valid-user {
            border-left: 6px solid #28a745;
            background: linear-gradient(135deg, #f8fff8 0%, #ffffff 100%);
        }

        .user-card.invalid-user {
            border-left: 6px solid #fd7e14;
            background: linear-gradient(135deg, #fff5f0 0%, #ffffff 100%);
            opacity: 0.7;
        }

        .user-card.high-confidence {
            border-left: 6px solid #28a745;
        }

        .user-card.medium-confidence {
            border-left: 6px solid #ffc107;
        }

        .user-card.low-confidence {
            border-left: 6px solid #fd7e14;
        }

        .similarity-badge {
            font-size: 12px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .user-rank {
            position: absolute;
            top: -10px;
            left: -10px;
            width: 28px;
            height: 28px;
            background: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .validation-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 10px;
            font-weight: bold;
        }

        .scan-status {
            border-radius: 8px;
            margin-top: 15px;
        }

        .no-users {
            color: #6c757d;
        }

        .user-avatar {
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .confidence-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .confidence-high {
            background-color: #28a745;
        }

        .confidence-medium {
            background-color: #ffc107;
        }

        .confidence-low {
            background-color: #fd7e14;
        }

        .user-details {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 10px;
            margin-top: 8px;
            font-size: 12px;
        }

        .user-progress {
            margin-top: 8px;
        }

        .progress {
            height: 8px;
            margin-bottom: 5px;
        }

        .progress-label {
            font-size: 11px;
            color: #6c757d;
            display: flex;
            justify-content: space-between;
        }

        .graph-container {
            position: relative;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .camera-preview {
                padding: 10px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-item {
                padding: 10px;
            }

            .stat-value {
                font-size: 18px;
            }

            .camera-controls .btn {
                margin-bottom: 5px;
                width: 100%;
            }

            .recognized-users-container {
                padding: 10px;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .camera-controls {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .user-card {
                padding: 12px;
            }

            .user-rank {
                width: 24px;
                height: 24px;
                font-size: 10px;
            }
        }

        /* Scrollbar Styling */
        .recognized-users-container::-webkit-scrollbar,
        .valid-users-list::-webkit-scrollbar {
            width: 6px;
        }

        .recognized-users-container::-webkit-scrollbar-track,
        .valid-users-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .recognized-users-container::-webkit-scrollbar-thumb,
        .valid-users-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .recognized-users-container::-webkit-scrollbar-thumb:hover,
        .valid-users-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Left Column - Camera & Results -->
            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Multiple Face Recognition</h4>
                        <p class="card-subtitle">Real-time face recognition untuk multiple users</p>
                    </div>
                    <div class="card-body">
                        <!-- Camera Section -->
                        <div class="camera-section mb-4">
                            <div class="camera-container">
                                <div id="cameraPreview" class="camera-preview mb-3 text-center">
                                    <div class="camera-wrapper">
                                        <video id="video" autoplay playsinline class="camera-video"></video>
                                        <canvas id="canvas" style="display: none;"></canvas>
                                    </div>
                                </div>

                                <div class="camera-controls mb-3 text-center">
                                    <button id="startCamera" class="btn btn-primary">
                                        <i class="fas fa-camera me-2"></i>Start Camera
                                    </button>
                                    <button id="stopCamera" class="btn btn-secondary" disabled>
                                        <i class="fas fa-stop me-2"></i>Stop Camera
                                    </button>
                                    <button id="captureMultiple" class="btn btn-success" disabled>
                                        <i class="fas fa-sync-alt me-2"></i>Start Auto Scan
                                    </button>
                                </div>

                                <div class="scan-status alert alert-info" id="scanStatus">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span id="statusText">Klik "Start Camera" untuk memulai</span>
                                </div>
                            </div>
                        </div>

                        <!-- All Recognized Users -->
                        <div class="recognized-users">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-users me-2"></i>
                                        Semua User yang Dikenali (Threshold: <span id="thresholdDisplay">70%</span>)
                                    </h6>
                                    <div>
                                        <span class="badge bg-primary me-2" id="totalUsers">0</span>
                                        <span class="badge bg-success" id="totalRecognized">0</span>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div id="recognizedUsersList" class="recognized-users-container">
                                        <div class="no-users text-center py-5 text-muted" id="noUsersMessage">
                                            <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                            <h6>Belum ada user yang dikenali</h6>
                                            <p class="small">Mulai scan untuk mendeteksi wajah user</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Statistics & Settings -->
            <div class="col-xl-4 col-lg-5">
                <!-- Real-time Statistics -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Real-time Statistics
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <div class="stat-item text-center">
                                <div class="stat-value text-primary" id="totalScans">0</div>
                                <div class="stat-label">Total Scans</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value text-success" id="successfulRecognition">0</div>
                                <div class="stat-label">Berhasil</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value text-info" id="totalUsersCount">0</div>
                                <div class="stat-label">Total User</div>
                            </div>
                        </div>
                        <div class="stats-grid mt-3">
                            <div class="stat-item text-center">
                                <div class="stat-value text-warning" id="currentSimilarity">0%</div>
                                <div class="stat-label">Similarity Tertinggi</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value text-danger" id="recognitionRate">0%</div>
                                <div class="stat-label">Success Rate</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value text-purple" id="validUsersCount">0</div>
                                <div class="stat-label">User Valid</div>
                            </div>
                        </div>

                        <!-- Valid Users List -->
                        <div class="valid-users-section mt-3">
                            <h6 class="mb-2">User yang Terdeteksi:</h6>
                            <div id="validUsersList" class="valid-users-list small">
                                <div class="text-muted text-center">Belum ada user yang terdeteksi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Face Matching Graph -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>Face Matching Graph
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="graph-container" style="height: 200px;">
                            <canvas id="matchingGraph"></canvas>
                        </div>
                        <div class="graph-legend mt-2">
                            <div class="d-flex justify-content-between small">
                                <span class="text-primary"><i class="fas fa-circle me-1"></i> Similarity</span>
                                <span class="text-danger"><i class="fas fa-circle me-1"></i> Threshold</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recognition Results Summary -->
                <div class="card mb-4" id="resultsSummary" style="display: none;">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-list me-2"></i>Results Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="summaryContent">
                            <!-- Summary akan diisi oleh JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Scan Settings -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-cog me-2"></i>Scan Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Scan Interval (detik)</label>
                            <input type="number" id="scanInterval" class="form-control" value="3" min="1" max="10">
                            <div class="form-text">Interval antara setiap scan</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Minimum Similarity: <span id="thresholdValueDisplay">70%</span></label>
                            <input type="range" id="similarityThreshold" class="form-range" min="50" max="90" value="70" step="5">
                            <div class="form-text">Threshold kemiripan wajah minimum (70% untuk validasi)</div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="autoSave" checked>
                            <label class="form-check-label" for="autoSave">Auto save recognized faces</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="showDetails" checked>
                            <label class="form-check-label" for="showDetails">Tampilkan detail recognition</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data user dari database (gantilah dengan data sebenarnya)
        const userImages = [{
                id: 1,
                name: "Ahmad Rizki",
                email: "ahmad@example.com",
                image: "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAAQABADASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k="
            },
            {
                id: 2,
                name: "Siti Nurhaliza",
                email: "siti@example.com",
                image: "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAAQABADASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k="
            },
            {
                id: 3,
                name: "Budi Santoso",
                email: "budi@example.com",
                image: "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAAQABADASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k="
            },
            {
                id: 4,
                name: "Maya Sari",
                email: "maya@example.com",
                image: "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAAQABADASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k="
            }
        ];

        class MultipleFaceRecognition {
            constructor() {
                this.video = document.getElementById('video');
                this.canvas = document.getElementById('canvas');
                this.ctx = this.canvas.getContext('2d');
                this.stream = null;
                this.isScanning = false;
                this.scanInterval = null;
                this.scanCount = 0;
                this.successfulRecognition = 0;
                this.recognizedUsers = new Map();
                this.allUsersCount = 0;
                this.validUsers = new Set(); // Untuk menyimpan user yang valid
                this.similarityHistory = []; // Untuk menyimpan history similarity untuk grafik
                this.matchingGraph = null; // Chart.js instance

                this.initializeEventListeners();
                this.initializeSettings();
                this.initializeGraph();
            }

            initializeEventListeners() {
                document.getElementById('startCamera').addEventListener('click', () => this.startCamera());
                document.getElementById('stopCamera').addEventListener('click', () => this.stopCamera());
                document.getElementById('captureMultiple').addEventListener('click', () => this.toggleAutoScan());

                document.getElementById('scanInterval').addEventListener('change', (e) => {
                    if (this.isScanning) {
                        this.restartAutoScan();
                    }
                });

                document.getElementById('similarityThreshold').addEventListener('input', (e) => {
                    const value = e.target.value;
                    document.getElementById('thresholdValueDisplay').textContent = value + '%';
                    document.getElementById('thresholdDisplay').textContent = value + '%';
                    localStorage.setItem('similarityThreshold', value);

                    // Update threshold line on graph
                    if (this.matchingGraph) {
                        this.matchingGraph.data.datasets[1].data = Array(this.similarityHistory.length).fill(value);
                        this.matchingGraph.update('none');
                    }
                });

                document.getElementById('showDetails').addEventListener('change', (e) => {
                    this.toggleDetails(e.target.checked);
                });
            }

            initializeSettings() {
                // Load saved settings jika ada
                const savedThreshold = localStorage.getItem('similarityThreshold');
                if (savedThreshold) {
                    document.getElementById('similarityThreshold').value = savedThreshold;
                    document.getElementById('thresholdValueDisplay').textContent = savedThreshold + '%';
                    document.getElementById('thresholdDisplay').textContent = savedThreshold + '%';
                } else {
                    // Default threshold 70%
                    document.getElementById('similarityThreshold').value = 70;
                    document.getElementById('thresholdValueDisplay').textContent = '70%';
                    document.getElementById('thresholdDisplay').textContent = '70%';
                }
            }

            initializeGraph() {
                const ctx = document.getElementById('matchingGraph').getContext('2d');
                this.matchingGraph = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                                label: 'Similarity',
                                data: [],
                                borderColor: '#007bff',
                                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Threshold',
                                data: [],
                                borderColor: '#dc3545',
                                backgroundColor: 'transparent',
                                borderWidth: 1,
                                borderDash: [5, 5],
                                fill: false,
                                pointRadius: 0
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                min: 0,
                                max: 100,
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            updateGraph(similarity) {
                if (!this.matchingGraph) return;

                // Add new data point
                this.similarityHistory.push(similarity);

                // Keep only last 20 data points
                if (this.similarityHistory.length > 20) {
                    this.similarityHistory.shift();
                }

                // Update labels (just numbers)
                const labels = Array.from({
                    length: this.similarityHistory.length
                }, (_, i) => i + 1);

                // Get current threshold
                const threshold = parseInt(document.getElementById('similarityThreshold').value);

                // Update chart data
                this.matchingGraph.data.labels = labels;
                this.matchingGraph.data.datasets[0].data = this.similarityHistory;
                this.matchingGraph.data.datasets[1].data = Array(this.similarityHistory.length).fill(threshold);

                // Update chart
                this.matchingGraph.update();
            }

            async startCamera() {
                try {
                    // Stop existing stream if any
                    if (this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                    }

                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            width: {
                                ideal: 640
                            },
                            height: {
                                ideal: 480
                            },
                            facingMode: 'user'
                        }
                    });

                    this.video.srcObject = this.stream;

                    // Wait for video to be ready
                    this.video.onloadedmetadata = () => {
                        document.getElementById('startCamera').disabled = true;
                        document.getElementById('stopCamera').disabled = false;
                        document.getElementById('captureMultiple').disabled = false;

                        this.updateStatus('Kamera aktif. Klik "Start Auto Scan" untuk memulai recognition.', 'success');
                    };

                } catch (error) {
                    console.error('Error accessing camera:', error);
                    this.updateStatus('Error: Tidak dapat mengakses kamera. Pastikan kamera tersedia dan izin diberikan.', 'danger');
                }
            }

            stopCamera() {
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                    this.stream = null;
                }

                this.stopAutoScan();

                document.getElementById('startCamera').disabled = false;
                document.getElementById('stopCamera').disabled = true;
                document.getElementById('captureMultiple').disabled = true;

                this.updateStatus('Kamera dihentikan.', 'warning');
            }

            toggleAutoScan() {
                if (this.isScanning) {
                    this.stopAutoScan();
                } else {
                    this.startAutoScan();
                }
            }

            startAutoScan() {
                if (!this.stream) {
                    this.updateStatus('Error: Kamera belum diaktifkan.', 'danger');
                    return;
                }

                this.isScanning = true;
                document.getElementById('captureMultiple').innerHTML = '<i class="fas fa-stop me-2"></i>Stop Auto Scan';
                document.getElementById('captureMultiple').classList.remove('btn-success');
                document.getElementById('captureMultiple').classList.add('btn-danger');

                const interval = parseInt(document.getElementById('scanInterval').value) * 1000;

                this.scanInterval = setInterval(() => {
                    this.captureAndRecognize();
                }, interval);

                this.updateStatus('Auto scan aktif. Sistem sedang melakukan face recognition...', 'info');
            }

            stopAutoScan() {
                this.isScanning = false;

                if (this.scanInterval) {
                    clearInterval(this.scanInterval);
                    this.scanInterval = null;
                }

                document.getElementById('captureMultiple').innerHTML = '<i class="fas fa-sync-alt me-2"></i>Start Auto Scan';
                document.getElementById('captureMultiple').classList.remove('btn-danger');
                document.getElementById('captureMultiple').classList.add('btn-success');

                this.updateStatus('Auto scan dihentikan.', 'warning');
            }

            restartAutoScan() {
                if (this.isScanning) {
                    this.stopAutoScan();
                    this.startAutoScan();
                }
            }

            async captureAndRecognize() {
                if (!this.stream) return;

                try {
                    this.scanCount++;
                    this.updateStats();

                    this.updateStatus('Mengambil gambar dan melakukan recognition...', 'info');

                    // Capture frame dari video
                    this.canvas.width = this.video.videoWidth;
                    this.canvas.height = this.video.videoHeight;

                    // Apply mirror correction untuk canvas
                    this.ctx.save();
                    this.ctx.scale(-1, 1);
                    this.ctx.drawImage(this.video, -this.canvas.width, 0, this.canvas.width, this.canvas.height);
                    this.ctx.restore();

                    // Convert ke base64
                    const imageData = this.canvas.toDataURL('image/jpeg', 0.8);

                    // Kirim ke server untuk recognition dengan data user yang sebenarnya
                    const response = await this.sendForRecognition(imageData);

                    if (response.success) {
                        this.processRecognitionResults(response);
                    } else {
                        this.updateStatus('Recognition gagal: ' + (response.error || 'Unknown error'), 'danger');
                    }

                } catch (error) {
                    console.error('Recognition error:', error);
                    this.updateStatus('Error selama recognition: ' + error.message, 'danger');
                }
            }

            async sendForRecognition(imageData) {
                try {
                    const threshold = document.getElementById('similarityThreshold').value;

                    // Kirim data user_images ke server untuk matching
                    const response = await fetch('/face/recognize-multiple', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            face_image: imageData,
                            user_images: userImages, // Kirim data user_images ke server
                            min_similarity: threshold
                        })
                    });

                    return await response.json();
                } catch (error) {
                    // Fallback ke simulasi jika server tidak tersedia
                    console.warn('Server tidak tersedia, menggunakan simulasi:', error);
                    return await this.simulateRecognitionWithRealUsers(imageData);
                }
            }

            // Simulasi dengan data user sebenarnya
            async simulateRecognitionWithRealUsers(imageData) {
                // Simulasi delay jaringan
                await new Promise(resolve => setTimeout(resolve, 800));

                // Simulasi proses face matching dengan user_images
                const recognizedUsers = [];
                let highestSimilarity = 0;

                // Proses matching dengan setiap user di database
                userImages.forEach(user => {
                    // Simulasi perhitungan similarity (dalam implementasi nyata, ini dilakukan di server)
                    const similarity = Math.floor(Math.random() * 30) + 60; // Random antara 60-90%

                    if (similarity > highestSimilarity) {
                        highestSimilarity = similarity;
                    }

                    // Tambahkan user jika melebihi threshold
                    if (similarity >= 70) {
                        recognizedUsers.push({
                            user_id: user.id,
                            user_name: user.name,
                            user_email: user.email,
                            similarity: similarity,
                            confidence: this.getConfidenceLevel(similarity),
                            comparison_notes: `Wajah cocok dengan ${user.name} di database`
                        });
                    }
                });

                return {
                    success: true,
                    recognized_users: recognizedUsers,
                    total_recognized: recognizedUsers.length,
                    total_users_checked: userImages.length,
                    highest_similarity: highestSimilarity
                };
            }

            getConfidenceLevel(similarity) {
                if (similarity >= 85) return 'high';
                if (similarity >= 70) return 'medium';
                return 'low';
            }

            processRecognitionResults(result) {
                if (result.recognized_users && result.recognized_users.length > 0) {
                    this.successfulRecognition++;
                    this.allUsersCount = result.total_users_checked || 0;

                    // Update graph with highest similarity
                    if (result.highest_similarity) {
                        this.updateGraph(result.highest_similarity);
                    }

                    // Update valid users
                    result.recognized_users.forEach(user => {
                        if (user.similarity >= 70) {
                            this.validUsers.add(user.user_id);
                        }
                    });

                    this.updateStats();

                    // SIMPAN SEMUA USER YANG DIKENALI
                    result.recognized_users.forEach(user => {
                        const userKey = `${user.user_id}_${Date.now()}`;

                        this.recognizedUsers.set(userKey, {
                            ...user,
                            last_recognized: new Date().toLocaleTimeString(),
                            detection_time: new Date(),
                            recognition_count: 1,
                            is_valid: user.similarity >= 70
                        });
                    });

                    this.updateRecognitionUI(result);
                    this.updateStatus(`✅ Berhasil mengenali ${result.recognized_users.length} user (${result.recognized_users.filter(u => u.similarity >= 70).length} valid)`, 'success');

                } else {
                    this.updateStatus('❌ Tidak ada user yang dikenali', 'warning');
                }

                // Update current similarity
                document.getElementById('currentSimilarity').textContent =
                    result.highest_similarity ? result.highest_similarity + '%' : '0%';
            }

            updateRecognitionUI(result) {
                // Update all recognized users
                this.showAllRecognizedUsers();

                // Update summary
                this.updateResultsSummary(result);

                // Update valid users list
                this.updateValidUsersList(result);

                // Update total users
                document.getElementById('totalUsers').textContent = this.recognizedUsers.size;
                document.getElementById('totalRecognized').textContent = result.total_recognized || 0;
                document.getElementById('validUsersCount').textContent = this.validUsers.size;
            }

            showAllRecognizedUsers() {
                const usersList = document.getElementById('recognizedUsersList');
                const noUsersMessage = document.getElementById('noUsersMessage');

                if (this.recognizedUsers.size === 0) {
                    noUsersMessage.style.display = 'block';
                    usersList.innerHTML = '<div class="no-users text-center py-5 text-muted" id="noUsersMessage"><i class="fas fa-users fa-3x mb-3 opacity-50"></i><h6>Belum ada user yang dikenali</h6><p class="small">Mulai scan untuk mendeteksi wajah user</p></div>';
                    return;
                }

                noUsersMessage.style.display = 'none';

                // Konversi Map ke Array dan urutkan berdasarkan similarity
                const sortedUsers = Array.from(this.recognizedUsers.values())
                    .sort((a, b) => b.similarity - a.similarity);

                let usersHTML = '';

                // TAMPILKAN SEMUA USER YANG DIKENALI
                sortedUsers.forEach((user, index) => {
                    usersHTML += this.createUserCard(user, index + 1);
                });

                usersList.innerHTML = usersHTML;
            }

            createUserCard(user, rank) {
                const confidenceClass = this.getConfidenceClass(user.confidence);
                const isValid = user.similarity >= 70;
                const validationClass = isValid ? 'valid-user' : 'invalid-user';
                const showDetails = document.getElementById('showDetails').checked;
                const isTopUser = rank <= 3;
                const threshold = parseInt(document.getElementById('similarityThreshold').value);

                return `
                    <div class="user-card ${confidenceClass} ${validationClass}">
                        ${isTopUser ? `<div class="user-rank bg-${this.getRankColor(rank)}">${rank}</div>` : ''}
                        <div class="validation-badge ${isValid ? 'bg-success' : 'bg-warning'}">
                            ${isValid ? 'VALID' : 'INVALID'}
                        </div>
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="user-avatar ${isValid ? 'bg-success' : 'bg-warning'} text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 55px; height: 55px; font-size: 20px;">
                                    ${user.user_name.charAt(0).toUpperCase()}
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 ${isValid ? 'text-success' : 'text-warning'}">
                                        <strong>${user.user_name}</strong>
                                    </h6>
                                    <span class="badge ${this.getSimilarityBadgeClass(user.similarity)} similarity-badge">
                                        ${user.similarity}%
                                    </span>
                                </div>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-envelope me-1"></i>${user.user_email}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <span class="confidence-indicator confidence-${user.confidence}"></span>
                                        ${user.confidence}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>${user.last_recognized}
                                    </small>
                                </div>
                                
                                <!-- Progress Bar untuk Similarity -->
                                <div class="user-progress">
                                    <div class="progress-label d-flex justify-content-between">
                                        <span>Similarity</span>
                                        <span>${user.similarity}%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar ${this.getProgressBarClass(user.similarity, threshold)}" 
                                             role="progressbar" 
                                             style="width: ${user.similarity}%" 
                                             aria-valuenow="${user.similarity}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="progress-label d-flex justify-content-between">
                                        <span>Threshold: ${threshold}%</span>
                                        <span>${user.similarity >= threshold ? 'PASS' : 'FAIL'}</span>
                                    </div>
                                </div>
                                
                                ${showDetails ? `
                                    <div class="user-details mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            ${user.comparison_notes || `User ID: ${user.user_id} | Confidence: ${user.confidence}`}
                                        </small>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }

            updateValidUsersList(result) {
                const validUsersList = document.getElementById('validUsersList');

                if (result.recognized_users && result.recognized_users.length > 0) {
                    const validUsers = result.recognized_users
                        .filter(user => user.similarity >= 70)
                        .slice(0, 5); // Tampilkan maksimal 5 user

                    if (validUsers.length > 0) {
                        let validUsersHTML = '';
                        validUsers.forEach(user => {
                            validUsersHTML += `
                                <div class="valid-user-item">
                                    <span class="user-name">${user.user_name}</span>
                                    <span class="user-similarity">${user.similarity}%</span>
                                </div>
                            `;
                        });
                        validUsersList.innerHTML = validUsersHTML;
                    } else {
                        validUsersList.innerHTML = '<div class="text-muted text-center">Tidak ada user yang valid</div>';
                    }
                } else {
                    validUsersList.innerHTML = '<div class="text-muted text-center">Belum ada user yang terdeteksi</div>';
                }
            }

            updateResultsSummary(result) {
                const resultsSummary = document.getElementById('resultsSummary');
                const summaryContent = document.getElementById('summaryContent');

                if (result.recognized_users && result.recognized_users.length > 0) {
                    const validUsers = result.recognized_users.filter(user => user.similarity >= 70);
                    const averageSimilarity = result.recognized_users.reduce((sum, user) => sum + user.similarity, 0) / result.recognized_users.length;

                    let summaryHTML = `
                        <div class="mb-3">
                            <strong>Total Dikenali:</strong> ${result.total_recognized} user<br>
                            <strong>User Valid (≥70%):</strong> ${validUsers.length} user<br>
                            <strong>Similarity Tertinggi:</strong> ${result.highest_similarity}%<br>
                            <strong>Rata-rata Similarity:</strong> ${averageSimilarity.toFixed(1)}%<br>
                            <strong>Total User Diperiksa:</strong> ${result.total_users_checked}
                        </div>
                        <div class="small">
                            <strong>Top ${Math.min(5, validUsers.length)} User Valid:</strong>
                            <ul class="list-unstyled mt-2">
                    `;

                    validUsers.slice(0, 5).forEach((user, index) => {
                        summaryHTML += `
                            <li class="mb-1">
                                <span class="badge bg-success me-1">${index + 1}</span>
                                ${user.user_name} <span class="text-muted">(${user.similarity}%)</span>
                            </li>
                        `;
                    });

                    summaryHTML += `</ul></div>`;
                    summaryContent.innerHTML = summaryHTML;
                    resultsSummary.style.display = 'block';
                } else {
                    resultsSummary.style.display = 'none';
                }
            }

            toggleDetails(show) {
                const userCards = document.querySelectorAll('.user-card');
                userCards.forEach(card => {
                    const details = card.querySelector('.user-details');
                    if (details) {
                        details.style.display = show ? 'block' : 'none';
                    }
                });
            }

            getConfidenceClass(confidence) {
                switch (confidence) {
                    case 'high':
                        return 'high-confidence';
                    case 'medium':
                        return 'medium-confidence';
                    default:
                        return 'low-confidence';
                }
            }

            getSimilarityBadgeClass(similarity) {
                if (similarity >= 80) return 'bg-success';
                if (similarity >= 70) return 'bg-warning text-dark';
                if (similarity >= 60) return 'bg-info';
                return 'bg-secondary';
            }

            getProgressBarClass(similarity, threshold) {
                if (similarity >= threshold) return 'bg-success';
                if (similarity >= threshold - 10) return 'bg-warning';
                return 'bg-danger';
            }

            getRankColor(rank) {
                switch (rank) {
                    case 1:
                        return 'success';
                    case 2:
                        return 'warning';
                    case 3:
                        return 'info';
                    default:
                        return 'secondary';
                }
            }

            updateStatus(message, type = 'info') {
                const statusElement = document.getElementById('scanStatus');
                const statusText = document.getElementById('statusText');

                statusText.textContent = message;
                statusElement.className = `alert alert-${type} scan-status`;
            }

            updateStats() {
                document.getElementById('totalScans').textContent = this.scanCount;
                document.getElementById('successfulRecognition').textContent = this.successfulRecognition;
                document.getElementById('totalUsersCount').textContent = this.allUsersCount;
                document.getElementById('validUsersCount').textContent = this.validUsers.size;

                // Calculate recognition rate
                const recognitionRate = this.scanCount > 0 ?
                    Math.round((this.successfulRecognition / this.scanCount) * 100) : 0;
                document.getElementById('recognitionRate').textContent = recognitionRate + '%';
            }
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            window.faceRecognition = new MultipleFaceRecognition();
        });
    </script>
</body>

</html>