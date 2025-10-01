from flask import Flask, request, jsonify
import cv2
import numpy as np
import base64
import io
import os
import logging
from PIL import Image
import time
import sys

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = Flask(__name__)

class RobustFaceRecognition:
    def __init__(self):
        logger.info("🚀 Initializing Robust Face Recognition...")
        
        self.available_methods = []
        self.face_cascade = None
        
        # Initialize OpenCV first (most reliable)
        try:
            self.face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
            if self.face_cascade.empty():
                logger.warning("⚠️ OpenCV cascade classifier failed to load")
                self.face_cascade = None
            else:
                self.available_methods.append('opencv')
                logger.info("✅ OpenCV loaded successfully")
        except Exception as e:
            logger.warning(f"⚠️ OpenCV initialization failed: {str(e)}")
        
        # Try face_recognition (good balance of accuracy and reliability)
        try:
            import face_recognition
            self.face_recognition = face_recognition
            self.available_methods.append('face_recognition')
            logger.info("✅ face_recognition loaded successfully")
        except ImportError as e:
            logger.warning(f"⚠️ face_recognition not available: {str(e)}")
            self.face_recognition = None
        
        # Ensure we have at least one method
        if not self.available_methods:
            logger.error("❌ No face recognition methods available!")
            raise Exception("No face recognition methods could be initialized")
        
        logger.info(f"🎯 Available methods: {self.available_methods}")

    def compare_faces(self, image1_base64, image2_path):
        """
        Robust face comparison using available methods with fallbacks
        """
        try:
            logger.info("🎯 Starting robust face comparison")
            start_time = time.time()
            
            # Decode base64 image
            image1_cv = self.decode_base64_image(image1_base64)
            if image1_cv is None:
                return self.error_response("Failed to decode base64 image")
            
            # Load profile image
            image2_cv = self.load_image(image2_path)
            if image2_cv is None:
                return self.error_response("Failed to load profile image")
            
            # Preprocess images
            image1_processed = self.preprocess_image(image1_cv)
            image2_processed = self.preprocess_image(image2_cv)
            
            # Try methods in order of reliability
            results = []
            methods_tried = []
            
            # Method 1: face_recognition (most reliable and accurate)
            if 'face_recognition' in self.available_methods:
                try:
                    result = self.compare_with_face_recognition(image1_processed, image2_processed)
                    methods_tried.append('face_recognition')
                    if result['success']:
                        results.append(result)
                        logger.info(f"🔍 Face Recognition: {result['similarity']}%")
                    else:
                        logger.warning(f"Face recognition failed: {result.get('error', 'Unknown error')}")
                except Exception as e:
                    logger.warning(f"Face recognition crashed: {str(e)}")
            
            # Method 2: OpenCV (fallback)
            if 'opencv' in self.available_methods and self.face_cascade is not None and len(results) == 0:
                try:
                    result = self.compare_with_opencv(image1_processed, image2_processed)
                    methods_tried.append('opencv')
                    if result['success']:
                        results.append(result)
                        logger.info(f"🔍 OpenCV: {result['similarity']}%")
                    else:
                        logger.warning(f"OpenCV failed: {result.get('error', 'Unknown error')}")
                except Exception as e:
                    logger.warning(f"OpenCV crashed: {str(e)}")
            
            # Select best result
            if results:
                best_result = max(results, key=lambda x: x['similarity'])
                processing_time = time.time() - start_time
                
                # Add confidence level and metadata
                best_result['confidence'] = self.calculate_confidence(best_result['similarity'])
                best_result['processing_time'] = round(processing_time, 2)
                best_result['methods_tried'] = methods_tried
                best_result['methods_available'] = self.available_methods
                
                logger.info(f"📊 FINAL: {best_result['similarity']}% - {best_result['method']} - Time: {processing_time:.2f}s")
                
                return best_result
            else:
                return self.error_response(f"All face recognition methods failed. Tried: {methods_tried}")
                
        except Exception as e:
            logger.error(f"❌ Error in face comparison: {str(e)}")
            return self.error_response(f"Comparison error: {str(e)}")

    def compare_with_face_recognition(self, img1, img2):
        """Use face_recognition library - most reliable method"""
        try:
            # Convert BGR to RGB
            img1_rgb = cv2.cvtColor(img1, cv2.COLOR_BGR2RGB)
            img2_rgb = cv2.cvtColor(img2, cv2.COLOR_BGR2RGB)
            
            # Get face encodings
            encodings1 = self.face_recognition.face_encodings(img1_rgb)
            encodings2 = self.face_recognition.face_encodings(img2_rgb)
            
            if len(encodings1) == 0:
                return {"success": False, "error": "No face detected in captured image"}
            if len(encodings2) == 0:
                return {"success": False, "error": "No face detected in profile image"}
            
            # Compare faces using face distance
            face_distance = self.face_recognition.face_distance([encodings1[0]], encodings2[0])[0]
            
            # Convert distance to similarity (face_distance ranges from 0 to 1, where 0 is identical)
            # Use non-linear scaling for better discrimination
            similarity = self.non_linear_similarity(face_distance)
            
            return {
                "success": True,
                "similarity": round(similarity, 2),
                "faces_detected_captured": len(encodings1),
                "faces_detected_profile": len(encodings2),
                "method": "face_recognition_cnn"
            }
        except Exception as e:
            logger.warning(f"Face recognition comparison failed: {str(e)}")
            return {"success": False, "error": str(e)}

    def non_linear_similarity(self, face_distance):
        """
        Convert face distance to similarity with non-linear scaling
        This provides better discrimination between similar and dissimilar faces
        """
        # Face distance: 0 = identical, 1 = completely different
        # Convert to similarity with better discrimination
        if face_distance < 0.4:
            # High similarity range - boost scores
            similarity = (1 - face_distance) * 100
        elif face_distance < 0.6:
            # Medium similarity range - linear
            similarity = (1 - face_distance) * 90
        else:
            # Low similarity range - penalize more
            similarity = (1 - face_distance) * 70
            
        return max(0, min(100, similarity))

    def compare_with_opencv(self, img1, img2):
        """Use OpenCV with advanced similarity calculation"""
        try:
            # Detect faces with multiple attempts
            faces1 = self.enhanced_face_detection(img1)
            faces2 = self.enhanced_face_detection(img2)
            
            if len(faces1) == 0:
                return {"success": False, "error": "No face detected in captured image"}
            if len(faces2) == 0:
                return {"success": False, "error": "No face detected in profile image"}
            
            # Extract and align face regions
            face1_region = self.extract_and_align_face(img1, faces1[0])
            face2_region = self.extract_and_align_face(img2, faces2[0])
            
            # Calculate similarity using multiple advanced methods
            similarity = self.calculate_advanced_similarity(face1_region, face2_region)
            
            return {
                "success": True,
                "similarity": round(similarity, 2),
                "faces_detected_captured": len(faces1),
                "faces_detected_profile": len(faces2),
                "method": "opencv_advanced"
            }
        except Exception as e:
            logger.warning(f"OpenCV comparison failed: {str(e)}")
            return {"success": False, "error": str(e)}

    def enhanced_face_detection(self, image):
        """Enhanced face detection with multiple attempts"""
        gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
        
        # Apply histogram equalization for better contrast
        gray = cv2.equalizeHist(gray)
        
        faces = []
        
        # Try multiple detection parameters
        detection_params = [
            {'scaleFactor': 1.1, 'minNeighbors': 5, 'minSize': (30, 30)},
            {'scaleFactor': 1.05, 'minNeighbors': 3, 'minSize': (20, 20)},
            {'scaleFactor': 1.2, 'minNeighbors': 7, 'minSize': (40, 40)}
        ]
        
        for params in detection_params:
            try:
                detected_faces = self.face_cascade.detectMultiScale(gray, **params)
                faces.extend([(x, y, w, h) for (x, y, w, h) in detected_faces])
            except:
                continue
        
        # Remove duplicate faces
        unique_faces = self.filter_duplicate_faces(faces)
        
        return unique_faces

    def filter_duplicate_faces(self, faces):
        """Remove duplicate face detections"""
        if not faces:
            return []
            
        unique_faces = []
        
        for face in faces:
            x, y, w, h = face
            is_duplicate = False
            
            for uf in unique_faces:
                ux, uy, uw, uh = uf
                # Check if faces overlap significantly
                overlap_x = abs(x - ux) < min(w, uw) * 0.5
                overlap_y = abs(y - uy) < min(h, uh) * 0.5
                
                if overlap_x and overlap_y:
                    is_duplicate = True
                    # Keep the larger face
                    if w * h > uw * uh:
                        unique_faces.remove(uf)
                        unique_faces.append(face)
                    break
            
            if not is_duplicate:
                unique_faces.append(face)
        
        return unique_faces

    def extract_and_align_face(self, image, face_coords):
        """Extract and align face region with advanced processing"""
        x, y, w, h = face_coords
        
        # Add padding around face
        padding = 0.25
        x1 = max(0, int(x - w * padding))
        y1 = max(0, int(y - h * padding))
        x2 = min(image.shape[1], int(x + w * (1 + padding)))
        y2 = min(image.shape[0], int(y + h * (1 + padding)))
        
        face_region = image[y1:y2, x1:x2]
        
        # Apply advanced preprocessing
        face_region = self.enhance_image_quality(face_region)
        
        # Resize to standard size
        face_resized = cv2.resize(face_region, (224, 224))
        
        return face_resized

    def enhance_image_quality(self, image):
        """Enhance image quality for better comparison"""
        try:
            # Convert to LAB color space
            lab = cv2.cvtColor(image, cv2.COLOR_BGR2LAB)
            l, a, b = cv2.split(lab)
            
            # Apply CLAHE to L-channel
            clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8,8))
            l = clahe.apply(l)
            
            # Merge back and convert to BGR
            enhanced_lab = cv2.merge([l, a, b])
            enhanced_image = cv2.cvtColor(enhanced_lab, cv2.COLOR_LAB2BGR)
            
            return enhanced_image
        except:
            return image

    def calculate_advanced_similarity(self, face1, face2):
        """Advanced similarity calculation for OpenCV fallback"""
        similarities = []
        
        # Histogram comparison
        hist_similarity = self.histogram_comparison(face1, face2)
        similarities.append(hist_similarity)
        
        # Structural similarity
        structural_similarity = self.structural_similarity(face1, face2)
        similarities.append(structural_similarity)
        
        # Feature matching
        feature_similarity = self.feature_matching(face1, face2)
        similarities.append(feature_similarity)
        
        # Color moment comparison
        color_similarity = self.color_moment_comparison(face1, face2)
        similarities.append(color_similarity)
        
        # Use weighted average
        weights = [0.25, 0.35, 0.25, 0.15]
        weighted_similarity = sum(s * w for s, w in zip(similarities, weights))
        
        return min(100, weighted_similarity * 100)

    def histogram_comparison(self, img1, img2):
        """Histogram comparison in multiple color spaces"""
        try:
            color_spaces = [
                ('BGR', img1, img2),
                ('HSV', cv2.cvtColor(img1, cv2.COLOR_BGR2HSV), cv2.cvtColor(img2, cv2.COLOR_BGR2HSV))
            ]
            
            similarities = []
            
            for color_space, cs_img1, cs_img2 in color_spaces:
                for channel in range(3):
                    hist1 = cv2.calcHist([cs_img1], [channel], None, [64], [0, 256])
                    hist2 = cv2.calcHist([cs_img2], [channel], None, [64], [0, 256])
                    
                    cv2.normalize(hist1, hist1)
                    cv2.normalize(hist2, hist2)
                    
                    similarity = cv2.compareHist(hist1, hist2, cv2.HISTCMP_CORREL)
                    normalized_similarity = max(0, (similarity + 1) / 2)
                    similarities.append(normalized_similarity)
            
            return np.mean(similarities)
        except:
            return 0.5

    def structural_similarity(self, img1, img2):
        """Calculate structural similarity index"""
        try:
            # Convert to grayscale
            gray1 = cv2.cvtColor(img1, cv2.COLOR_BGR2GRAY)
            gray2 = cv2.cvtColor(img2, cv2.COLOR_BGR2GRAY)
            
            # Ensure same size
            if gray1.shape != gray2.shape:
                gray2 = cv2.resize(gray2, (gray1.shape[1], gray1.shape[0]))
            
            # Calculate SSIM
            C1 = (0.01 * 255) ** 2
            C2 = (0.03 * 255) ** 2

            img1 = gray1.astype(np.float64)
            img2 = gray2.astype(np.float64)
            kernel = cv2.getGaussianKernel(11, 1.5)
            window = np.outer(kernel, kernel.transpose())

            mu1 = cv2.filter2D(img1, -1, window)[5:-5, 5:-5]
            mu2 = cv2.filter2D(img2, -1, window)[5:-5, 5:-5]
            mu1_sq = mu1 ** 2
            mu2_sq = mu2 ** 2
            mu1_mu2 = mu1 * mu2
            sigma1_sq = cv2.filter2D(img1 ** 2, -1, window)[5:-5, 5:-5] - mu1_sq
            sigma2_sq = cv2.filter2D(img2 ** 2, -1, window)[5:-5, 5:-5] - mu2_sq
            sigma12 = cv2.filter2D(img1 * img2, -1, window)[5:-5, 5:-5] - mu1_mu2

            ssim_map = ((2 * mu1_mu2 + C1) * (2 * sigma12 + C2)) / ((mu1_sq + mu2_sq + C1) * (sigma1_sq + sigma2_sq + C2))
            return np.mean(ssim_map)
        except:
            return 0.5

    def feature_matching(self, img1, img2):
        """Feature matching using ORB"""
        try:
            orb = cv2.ORB_create(1000)
            kp1, des1 = orb.detectAndCompute(img1, None)
            kp2, des2 = orb.detectAndCompute(img2, None)
            
            if des1 is None or des2 is None:
                return 0.3
            
            bf = cv2.BFMatcher(cv2.NORM_HAMMING, crossCheck=True)
            matches = bf.match(des1, des2)
            matches = sorted(matches, key=lambda x: x.distance)
            
            if len(matches) > 10:
                good_matches = matches[:50]
                similarity = len(good_matches) / min(len(kp1), len(kp2))
            else:
                similarity = 0.1
            
            return min(1.0, similarity)
        except:
            return 0.3

    def color_moment_comparison(self, img1, img2):
        """Compare color moments"""
        try:
            moments1 = cv2.moments(cv2.cvtColor(img1, cv2.COLOR_BGR2HSV))
            moments2 = cv2.moments(cv2.cvtColor(img2, cv2.COLOR_BGR2HSV))
            
            # Compare key moments
            moment_diff = abs(moments1['m00'] - moments2['m00']) / max(moments1['m00'], moments2['m00'])
            similarity = 1.0 - min(moment_diff, 1.0)
            
            return similarity
        except:
            return 0.4

    def decode_base64_image(self, image_base64):
        """Decode base64 image with validation"""
        try:
            if ',' in image_base64:
                image_base64 = image_base64.split(',')[1]
            
            image_data = base64.b64decode(image_base64)
            image_pil = Image.open(io.BytesIO(image_data))
            image_cv = cv2.cvtColor(np.array(image_pil), cv2.COLOR_RGB2BGR)
            
            return image_cv
        except Exception as e:
            logger.error(f"Base64 decoding error: {str(e)}")
            return None

    def load_image(self, image_path):
        """Load image from file path"""
        try:
            if not os.path.exists(image_path):
                return None
            
            image = cv2.imread(image_path)
            return image
        except Exception as e:
            logger.error(f"Image loading error: {str(e)}")
            return None

    def preprocess_image(self, image):
        """Preprocess image for better face detection"""
        try:
            # Resize if too large
            height, width = image.shape[:2]
            if max(height, width) > 1200:
                scale = 1200 / max(height, width)
                new_width = int(width * scale)
                new_height = int(height * scale)
                image = cv2.resize(image, (new_width, new_height))
            
            return image
        except Exception:
            return image

    def calculate_confidence(self, similarity):
        """Calculate confidence based on similarity score"""
        if similarity >= 85:
            return "very_high"
        elif similarity >= 75:
            return "high"
        elif similarity >= 65:
            return "medium"
        elif similarity >= 55:
            return "low"
        else:
            return "very_low"

    def error_response(self, message):
        """Standard error response"""
        return {
            "success": False,
            "error": message,
            "similarity": 0,
            "faces_detected_captured": 0,
            "faces_detected_profile": 0
        }

# Initialize face recognition with error handling
try:
    face_recog = RobustFaceRecognition()
    logger.info("✅ Face recognition system initialized successfully")
except Exception as e:
    logger.error(f"❌ Failed to initialize face recognition: {str(e)}")
    face_recog = None

@app.route('/api/enhanced-compare-faces', methods=['POST'])
def enhanced_compare_faces():
    try:
        if face_recog is None:
            return jsonify({
                "success": False,
                "error": "Face recognition system not initialized"
            }), 500
        
        data = request.json
        
        if not data or 'image1' not in data or 'image2_path' not in data:
            return jsonify({
                "success": False,
                "error": "Missing required parameters"
            }), 400
        
        result = face_recog.compare_faces(
            data['image1'],
            data['image2_path']
        )
        
        return jsonify(result)
        
    except Exception as e:
        logger.error(f"❌ API error: {str(e)}")
        return jsonify({
            "success": False,
            "error": f"API error: {str(e)}"
        }), 500

@app.route('/api/health', methods=['GET'])
def health_check():
    status = "healthy" if face_recog and face_recog.available_methods else "unhealthy"
    
    return jsonify({
        "status": status, 
        "service": "Robust Face Recognition API",
        "version": "4.1",
        "available_methods": face_recog.available_methods if face_recog else [],
        "timestamp": time.time()
    })

@app.route('/api/test', methods=['GET'])
def test_endpoint():
    """Test endpoint to verify API is working"""
    return jsonify({
        "message": "Face Recognition API is running",
        "timestamp": time.time(),
        "methods_available": face_recog.available_methods if face_recog else [],
        "status": "operational" if face_recog and face_recog.available_methods else "failed"
    })

if __name__ == '__main__':
    if face_recog and face_recog.available_methods:
        logger.info("🚀 Starting Robust Face Recognition API on http://127.0.0.1:5000")
        logger.info(f"📡 Available methods: {face_recog.available_methods}")
        app.run(host='127.0.0.1', port=5000, debug=False)
    else:
        logger.error("❌ Cannot start API: No face recognition methods available")
        sys.exit(1)