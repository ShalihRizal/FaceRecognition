<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EnhancedFaceRecognitionService
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = 'http://127.0.0.1:5000/api/enhanced-compare-faces';
    }

    /**
     * Compare with multiple profile images and return the best match
     */
    /**
     * Compare with multiple profile images and return the best match
     */
    public function compareFacesWithMultipleImages($imageBase64, $userImagesArray, $userId)
    {
        try {
            Log::info('🔍 Starting MULTIPLE face recognition', [
                'user_id' => $userId,
                'total_profile_images' => count($userImagesArray),
                'profile_images' => $userImagesArray
            ]);

            if (empty($userImagesArray)) {
                return [
                    'success' => false,
                    'error' => 'No profile images available for comparison'
                ];
            }

            $bestResult = [
                'success' => false,
                'similarity' => 0,
                'best_match_image' => null,
                'all_results' => [],
                'statistics' => []
            ];

            $similarities = [];

            // Compare with each profile image
            foreach ($userImagesArray as $index => $profileImage) {
                Log::info("🔄 Comparing with profile image {$index}: {$profileImage}");

                $userImagePath = $this->getUserImagePath($profileImage);

                if ($userImagePath && Storage::disk('public')->exists($userImagePath)) {
                    $fullUserImagePath = Storage::disk('public')->path($userImagePath);

                    $result = $this->compareFacesViaApi($imageBase64, $fullUserImagePath);

                    // Store individual result
                    $individualResult = [
                        'profile_image' => $profileImage,
                        'similarity' => $result['success'] ? $result['similarity'] : 0,
                        'success' => $result['success'],
                        'error' => $result['error'] ?? null,
                        'method' => $result['method'] ?? 'unknown',
                        'confidence' => $result['confidence'] ?? 'unknown'
                    ];

                    $bestResult['all_results'][] = $individualResult;

                    // Collect successful similarities for statistics
                    if ($result['success']) {
                        $similarities[] = $result['similarity'];

                        // Update best result if this one is better
                        if ($result['similarity'] > $bestResult['similarity']) {
                            $bestResult['success'] = true;
                            $bestResult['similarity'] = $result['similarity'];
                            $bestResult['best_match_image'] = $profileImage;
                            $bestResult['method'] = $result['method'] ?? 'multiple_images_comparison';
                            $bestResult['confidence'] = $result['confidence'] ?? 'unknown';
                        }
                    }

                    Log::info("📊 Comparison result for {$profileImage}: " .
                        ($result['success'] ? "{$result['similarity']}%" : "Failed: {$result['error']}"));
                } else {
                    Log::warning('Profile image not found', [
                        'profile_image' => $profileImage,
                        'user_id' => $userId
                    ]);

                    $bestResult['all_results'][] = [
                        'profile_image' => $profileImage,
                        'similarity' => 0,
                        'success' => false,
                        'error' => 'Profile image file not found'
                    ];
                }
            }

            // Calculate statistics
            if (!empty($similarities)) {
                $bestResult['statistics'] = [
                    'average_similarity' => round(array_sum($similarities) / count($similarities), 2),
                    'max_similarity' => max($similarities),
                    'min_similarity' => min($similarities),
                    'total_comparisons' => count($userImagesArray),
                    'successful_comparisons' => count($similarities),
                    'success_rate' => round((count($similarities) / count($userImagesArray)) * 100, 2),
                    'similarity_std_dev' => $this->calculateStandardDeviation($similarities)
                ];

                // Apply advanced validation
                $bestResult = $this->applyAdvancedValidation($bestResult, $similarities);
            }

            Log::info('🎯 MULTIPLE Face Recognition completed', [
                'best_similarity' => $bestResult['similarity'],
                'best_match_image' => $bestResult['best_match_image'],
                'average_similarity' => $bestResult['statistics']['average_similarity'] ?? 0,
                'successful_comparisons' => $bestResult['statistics']['successful_comparisons'] ?? 0,
                'validation_passed' => $bestResult['validation_passed'] ?? false
            ]);

            return $bestResult;
        } catch (\Exception $e) {
            Log::error('❌ MULTIPLE Face Recognition error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Multiple comparison error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Apply advanced validation rules for better accuracy
     */
    private function applyAdvancedValidation($results, $similarities)
    {
        if (!$results['success'] || empty($similarities)) {
            $results['validation_passed'] = false;
            return $results;
        }

        $stats = $results['statistics'];
        $maxSimilarity = $stats['max_similarity'];
        $averageSimilarity = $stats['average_similarity'];
        $stdDev = $stats['similarity_std_dev'];
        $successRate = $stats['success_rate'];

        // Advanced validation rules
        $validationRules = [
            // Rule 1: High maximum similarity
            'high_max_similarity' => $maxSimilarity >= 70,

            // Rule 2: Good average similarity
            'good_average_similarity' => $averageSimilarity >= 55,

            // Rule 3: Consistency (low standard deviation)
            'good_consistency' => $stdDev < 15,

            // Rule 4: Good success rate
            'good_success_rate' => $successRate >= 60,

            // Rule 5: Significant difference between max and average
            'significant_difference' => ($maxSimilarity - $averageSimilarity) > 10,

            // Rule 6: Multiple successful comparisons
            'multiple_successes' => count($similarities) >= 2
        ];

        $results['validation_rules'] = $validationRules;

        // Calculate validation score
        $passedRules = array_sum($validationRules);
        $totalRules = count($validationRules);
        $validationScore = $passedRules / $totalRules;

        $results['validation_score'] = round($validationScore * 100, 2);
        $results['validation_passed'] = $validationScore >= 0.7; // 70% rules must pass

        // Adjust final similarity based on validation
        if ($results['validation_passed']) {
            // Boost confidence for well-validated results
            if ($validationScore >= 0.9) {
                $results['similarity'] = min(100, $results['similarity'] + 2);
                $results['confidence'] = 'very_high_validated';
            } elseif ($validationScore >= 0.8) {
                $results['confidence'] = 'high_validated';
            } else {
                $results['confidence'] = 'medium_validated';
            }
        } else {
            // Penalize poorly validated results
            $results['similarity'] = max(0, $results['similarity'] - 10);
            $results['confidence'] = 'low_validation_failed';

            Log::warning('⚠️ Advanced validation failed', [
                'validation_score' => $results['validation_score'],
                'passed_rules' => $passedRules,
                'total_rules' => $totalRules,
                'rules' => $validationRules
            ]);
        }

        return $results;
    }

    /**
     * Calculate standard deviation for similarity scores
     */
    private function calculateStandardDeviation($similarities)
    {
        if (count($similarities) < 2) {
            return 0;
        }

        $average = array_sum($similarities) / count($similarities);
        $sumOfSquares = 0;

        foreach ($similarities as $value) {
            $sumOfSquares += pow($value - $average, 2);
        }

        return round(sqrt($sumOfSquares / (count($similarities) - 1)), 2);
    }

    /**
     * Apply strict validation rules to prevent false positives
     */
    private function applyStrictValidation($results)
    {
        if (!$results['success'] || !isset($results['statistics'])) {
            return $results;
        }

        $averageSimilarity = $results['statistics']['average_similarity'];
        $maxSimilarity = $results['statistics']['max_similarity'];
        $successRate = $results['statistics']['success_rate'];

        // Rule 1: Maximum similarity must be significantly higher than average
        $similaritySpread = $maxSimilarity - $averageSimilarity;

        // Rule 2: Check consistency across multiple images
        $consistencyThreshold = 20; // Maximum spread between min and max similarity

        // Rule 3: Success rate should be high
        $minSuccessRate = 50; // At least 50% of comparisons should be successful

        $validationResults = [
            'similarity_spread_ok' => $similaritySpread > 5,
            'consistency_ok' => ($results['statistics']['max_similarity'] - $results['statistics']['min_similarity']) < $consistencyThreshold,
            'success_rate_ok' => $successRate >= $minSuccessRate,
            'high_confidence_ok' => $maxSimilarity >= 70
        ];

        $results['validation'] = $validationResults;

        // If validation fails, adjust the result
        $passedValidations = array_sum($validationResults);
        $totalValidations = count($validationResults);

        if ($passedValidations < $totalValidations * 0.7) {
            Log::warning('⚠️ Strict validation failed', [
                'passed' => $passedValidations,
                'total' => $totalValidations,
                'validation_results' => $validationResults
            ]);

            // Reduce confidence for validation failures
            $results['similarity'] = max(0, $results['similarity'] - 10);
            $results['confidence'] = 'low_due_to_validation_failure';
        }

        return $results;
    }

    /**
     * Enhanced face comparison with single image
     */
    public function compareFacesViaApi($imageBase64, $image2Path)
    {
        try {
            Log::info('🔍 Starting face recognition API call', [
                'profile_image_path' => $image2Path,
                'base64_length' => strlen($imageBase64),
                'file_exists' => file_exists($image2Path)
            ]);

            // Validate profile image
            if (!file_exists($image2Path)) {
                return [
                    'success' => false,
                    'error' => 'Profile image file not found: ' . $image2Path
                ];
            }

            // Check file size and type
            $profileImageSize = filesize($image2Path);
            if ($profileImageSize < 1024) {
                return [
                    'success' => false,
                    'error' => 'Profile image too small: ' . $profileImageSize . ' bytes'
                ];
            }

            $client = new \GuzzleHttp\Client();

            $response = $client->post($this->apiUrl, [
                'json' => [
                    'image1' => $imageBase64,
                    'image2_path' => $image2Path
                ],
                'timeout' => 60,
                'connect_timeout' => 15
            ]);

            $result = json_decode($response->getBody(), true);

            Log::info('🎯 Face Recognition API response', ['result' => $result]);

            return $result;
        } catch (\Exception $e) {
            Log::error('❌ Face Recognition API error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Service unavailable: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Multiple comparison methods as fallback - FIXED METHOD NAME
     */
    public function compareFacesWithFallback($imageBase64, $userImagesArray, $userId)
    {
        Log::info('🔄 Starting face comparison with fallback', [
            'user_id' => $userId,
            'total_images' => count($userImagesArray)
        ]);

        // Method 1: Enhanced API with multiple images
        $result = $this->compareFacesWithMultipleImages($imageBase64, $userImagesArray, $userId);

        // If enhanced fails, try basic single image comparison as fallback
        if (!$result['success'] && !empty($userImagesArray)) {
            Log::warning('⚠️ Multiple images comparison failed, trying single image fallback');

            // Try with the first profile image
            $firstImage = $userImagesArray[0];
            $userImagePath = $this->getUserImagePath($firstImage);

            if ($userImagePath && Storage::disk('public')->exists($userImagePath)) {
                $fullUserImagePath = Storage::disk('public')->path($userImagePath);
                $singleResult = $this->compareFacesViaApi($imageBase64, $fullUserImagePath);

                if ($singleResult['success']) {
                    $result = [
                        'success' => true,
                        'similarity' => $singleResult['similarity'],
                        'best_match_image' => $firstImage,
                        'method' => $singleResult['method'] . '_single_fallback',
                        'statistics' => [
                            'average_similarity' => $singleResult['similarity'],
                            'max_similarity' => $singleResult['similarity'],
                            'min_similarity' => $singleResult['similarity'],
                            'total_comparisons' => 1,
                            'successful_comparisons' => 1,
                            'success_rate' => 100
                        ],
                        'validation' => [
                            'similarity_spread_ok' => true,
                            'consistency_ok' => true,
                            'success_rate_ok' => true,
                            'high_confidence_ok' => $singleResult['similarity'] >= 70
                        ]
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * Find user image path
     */
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

        return null;
    }

    /**
     * Check if Python service is healthy
     */
    public function healthCheck()
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get('http://127.0.0.1:5000/api/health', [
                'timeout' => 5,
                'connect_timeout' => 3
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Simple single image comparison (for backward compatibility)
     */
    public function compareFaces($imageBase64, $image2Path)
    {
        return $this->compareFacesViaApi($imageBase64, $image2Path);
    }
}
