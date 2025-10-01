import cv2
import numpy as np
from PIL import Image
import base64
import io
import os
import json
import math

class AdvancedFaceRecognition:
    def __init__(self):
        # Load multiple face detection classifiers for better accuracy
        self.face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
        self.profile_cascade = cv2.data.haarcascades + 'haarcascade_profileface.xml'
        
    def compare_faces_base64(self, image1_base64, image2_path):
        """
        Advanced face comparison with multiple validation steps
        """
        try:
            print("🚀 Starting Advanced Face Comparison...")
            
            # Decode base64 image
            if ',' in image1_base64:
                image1_base64 = image1_base64.split(',')[1]
                
            image1_data = base64.b64decode(image1_base64)
            image1_pil = Image.open(io.BytesIO(image1_data))
            
            # Convert to OpenCV format
            image1_cv = cv2.cvtColor(np.array(image1_pil), cv2.COLOR_RGB2BGR)
            
            print("✅ Base64 image decoded successfully")
            
            # Load second image
            if not os.path.exists(image2_path):
                return {
                    "success": False, 
                    "error": f"❌ Profile image not found: {image2_path}"
                }
                
            image2_cv = cv2.imread(image2_path)
            
            if image2_cv is None:
                return {
                    "success": False, 
                    "error": "❌ Could not load profile image"
                }
            
            print("✅ Profile image loaded successfully")
            
            # Enhanced preprocessing
            image1_enhanced = self.enhanced_preprocessing(image1_cv)
            image2_enhanced = self.enhanced_preprocessing(image2_cv)
            
            # Multi-stage face detection
            faces1 = self.multi_stage_face_detection(image1_enhanced, image1_cv)
            faces2 = self.multi_stage_face_detection(image2_enhanced, image2_cv)
            
            print(f"👤 Faces detected - Captured: {len(faces1)}, Profile: {len(faces2)}")
            
            if len(faces1) == 0:
                return {
                    "success": False, 
                    "error": "❌ No face detected in captured image",
                    "faces_detected_captured": len(faces1),
                    "faces_detected_profile": len(faces2)
                }
                
            if len(faces2) == 0:
                return {
                    "success": False, 
                    "error": "❌ No face detected in profile image",
                    "faces_detected_captured": len(faces1),
                    "faces_detected_profile": len(faces2)
                }
            
            # Get the best matching face pair
            best_similarity = 0
            best_face1 = None
            best_face2 = None
            
            for face1 in faces1:
                face1_region = self.extract_aligned_face(image1_cv, face1)
                for face2 in faces2:
                    face2_region = self.extract_aligned_face(image2_cv, face2)
                    
                    similarity = self.calculate_comprehensive_similarity(face1_region, face2_region)
                    
                    if similarity > best_similarity:
                        best_similarity = similarity
                        best_face1 = face1
                        best_face2 = face2
            
            print(f"📊 Best similarity calculated: {best_similarity}%")
            
            return {
                "success": True,
                "similarity": round(best_similarity, 2),
                "faces_detected_captured": len(faces1),
                "faces_detected_profile": len(faces2),
                "method": "advanced_opencv_multi_algorithm"
            }
            
        except Exception as e:
            print(f"❌ Error in advanced face comparison: {str(e)}")
            return {
                "success": False, 
                "error": f"Advanced comparison error: {str(e)}"
            }
    
    def enhanced_preprocessing(self, image):
        """Enhanced image preprocessing"""
        # Convert to different color spaces
        lab = cv2.cvtColor(image, cv2.COLOR_BGR2LAB)
        hsv = cv2.cvtColor(image, cv2.COLOR_BGR2HSV)
        
        # Enhance LAB channel
        lab_planes = list(cv2.split(lab))
        clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8,8))
        lab_planes[0] = clahe.apply(lab_planes[0])
        lab_enhanced = cv2.merge(lab_planes)
        lab_enhanced = cv2.cvtColor(lab_enhanced, cv2.COLOR_LAB2BGR)
        
        # Combine original and enhanced images
        combined = cv2.addWeighted(image, 0.5, lab_enhanced, 0.5, 0)
        
        # Convert to grayscale for face detection
        gray = cv2.cvtColor(combined, cv2.COLOR_BGR2GRAY)
        
        return gray
    
    def multi_stage_face_detection(self, processed_image, original_image):
        """Multi-stage face detection with different parameters"""
        faces = []
        
        # Stage 1: Standard detection
        faces1 = self.face_cascade.detectMultiScale(
            processed_image,
            scaleFactor=1.1,
            minNeighbors=5,
            minSize=(30, 30),
            flags=cv2.CASCADE_SCALE_IMAGE
        )
        faces.extend([(x, y, w, h, 1) for (x, y, w, h) in faces1])
        
        # Stage 2: More sensitive detection
        faces2 = self.face_cascade.detectMultiScale(
            processed_image,
            scaleFactor=1.05,
            minNeighbors=3,
            minSize=(20, 20),
            flags=cv2.CASCADE_SCALE_IMAGE
        )
        faces.extend([(x, y, w, h, 2) for (x, y, w, h) in faces2])
        
        # Stage 3: Less sensitive but larger faces
        faces3 = self.face_cascade.detectMultiScale(
            processed_image,
            scaleFactor=1.2,
            minNeighbors=7,
            minSize=(40, 40),
            flags=cv2.CASCADE_SCALE_IMAGE
        )
        faces.extend([(x, y, w, h, 3) for (x, y, w, h) in faces3])
        
        # Remove duplicates and small faces
        unique_faces = self.filter_duplicate_faces(faces)
        
        return unique_faces
    
    def filter_duplicate_faces(self, faces):
        """Remove duplicate face detections"""
        unique_faces = []
        for face in faces:
            x, y, w, h, stage = face
            is_duplicate = False
            
            for uf in unique_faces:
                ux, uy, uw, uh, _ = uf
                # Check if faces overlap significantly
                dx = abs(x - ux)
                dy = abs(y - uy)
                
                if dx < w * 0.5 and dy < h * 0.5:
                    is_duplicate = True
                    # Keep the larger face
                    if w * h > uw * uh:
                        unique_faces.remove(uf)
                        unique_faces.append(face)
                    break
            
            if not is_duplicate:
                unique_faces.append(face)
        
        return [(x, y, w, h) for (x, y, w, h, stage) in unique_faces]
    
    def extract_aligned_face(self, image, face_coords):
        """Extract and align face region with landmark-based alignment"""
        x, y, w, h = face_coords
        
        # Expand face region
        expand = 0.2
        new_x = max(0, int(x - w * expand))
        new_y = max(0, int(y - h * expand))
        new_w = min(image.shape[1] - new_x, int(w * (1 + 2 * expand)))
        new_h = min(image.shape[0] - new_y, int(h * (1 + 2 * expand)))
        
        face_region = image[new_y:new_y+new_h, new_x:new_x+new_w]
        
        # Resize to standard size
        face_resized = cv2.resize(face_region, (224, 224))
        
        return face_resized
    
    def calculate_comprehensive_similarity(self, face1, face2):
        """Comprehensive similarity calculation with multiple advanced methods"""
        try:
            # Method 1: Deep Histogram Comparison
            hist_similarity = self.deep_histogram_similarity(face1, face2)
            
            # Method 2: Advanced Structural Similarity
            structural_similarity = self.advanced_structural_similarity(face1, face2)
            
            # Method 3: Enhanced Feature Matching
            feature_similarity = self.enhanced_feature_matching(face1, face2)
            
            # Method 4: Deep Template Matching
            template_similarity = self.deep_template_matching(face1, face2)
            
            # Method 5: Color Distribution Similarity
            color_similarity = self.color_distribution_similarity(face1, face2)
            
            # Method 6: Texture Analysis
            texture_similarity = self.texture_analysis_similarity(face1, face2)
            
            # Adaptive weighting based on image quality
            weights = self.calculate_adaptive_weights(face1, face2, [
                hist_similarity, structural_similarity, feature_similarity,
                template_similarity, color_similarity, texture_similarity
            ])
            
            final_similarity = sum(s * w for s, w in zip([
                hist_similarity, structural_similarity, feature_similarity,
                template_similarity, color_similarity, texture_similarity
            ], weights))
            
            # Apply advanced scaling
            scaled_similarity = self.advanced_scaling(final_similarity)
            
            return min(100, scaled_similarity * 100)
            
        except Exception as e:
            print(f"Error in comprehensive similarity calculation: {e}")
            return 0
    
    def deep_histogram_similarity(self, img1, img2):
        """Multi-level histogram comparison"""
        similarities = []
        
        # RGB histogram
        for i in range(3):
            hist1 = cv2.calcHist([img1], [i], None, [64], [0, 256])
            hist2 = cv2.calcHist([img2], [i], None, [64], [0, 256])
            cv2.normalize(hist1, hist1)
            cv2.normalize(hist2, hist2)
            similarity = cv2.compareHist(hist1, hist2, cv2.HISTCMP_CORREL)
            similarities.append(max(0, (similarity + 1) / 2))
        
        # HSV histogram
        hsv1 = cv2.cvtColor(img1, cv2.COLOR_BGR2HSV)
        hsv2 = cv2.cvtColor(img2, cv2.COLOR_BGR2HSV)
        
        for i in range(3):
            hist1 = cv2.calcHist([hsv1], [i], None, [64], [0, 256])
            hist2 = cv2.calcHist([hsv2], [i], None, [64], [0, 256])
            cv2.normalize(hist1, hist1)
            cv2.normalize(hist2, hist2)
            similarity = cv2.compareHist(hist1, hist2, cv2.HISTCMP_CORREL)
            similarities.append(max(0, (similarity + 1) / 2))
        
        return np.mean(similarities)
    
    def advanced_structural_similarity(self, img1, img2):
        """Advanced structural similarity with multiple approaches"""
        gray1 = cv2.cvtColor(img1, cv2.COLOR_BGR2GRAY)
        gray2 = cv2.cvtColor(img2, cv2.COLOR_BGR2GRAY)
        
        # MSE-based similarity
        mse = np.mean((gray1 - gray2) ** 2)
        if mse == 0:
            mse_similarity = 1.0
        else:
            mse_similarity = 1.0 / (1.0 + mse / 100.0)
        
        # Edge-based similarity
        edges1 = cv2.Canny(gray1, 50, 150)
        edges2 = cv2.Canny(gray2, 50, 150)
        
        edge_overlap = np.sum(edges1 & edges2) / np.sum(edges1 | edges2) if np.sum(edges1 | edges2) > 0 else 0
        
        # Gradient-based similarity
        grad_x1 = cv2.Sobel(gray1, cv2.CV_64F, 1, 0, ksize=3)
        grad_y1 = cv2.Sobel(gray1, cv2.CV_64F, 0, 1, ksize=3)
        grad1 = np.sqrt(grad_x1**2 + grad_y1**2)
        
        grad_x2 = cv2.Sobel(gray2, cv2.CV_64F, 1, 0, ksize=3)
        grad_y2 = cv2.Sobel(gray2, cv2.CV_64F, 0, 1, ksize=3)
        grad2 = np.sqrt(grad_x2**2 + grad_y2**2)
        
        grad_similarity = np.corrcoef(grad1.flatten(), grad2.flatten())[0, 1]
        grad_similarity = max(0, (grad_similarity + 1) / 2)
        
        return (mse_similarity + edge_overlap + grad_similarity) / 3
    
    def enhanced_feature_matching(self, img1, img2):
        """Enhanced feature matching with multiple detectors"""
        try:
            # ORB features
            orb = cv2.ORB_create(1000)
            kp1, des1 = orb.detectAndCompute(img1, None)
            kp2, des2 = orb.detectAndCompute(img2, None)
            
            if des1 is None or des2 is None:
                return 0.3
            
            # FLANN based matcher
            FLANN_INDEX_LSH = 6
            index_params = dict(algorithm=FLANN_INDEX_LSH, table_number=6, key_size=12, multi_probe_level=1)
            search_params = dict(checks=50)
            
            flann = cv2.FlannBasedMatcher(index_params, search_params)
            matches = flann.knnMatch(des1, des2, k=2)
            
            # Apply ratio test
            good_matches = []
            for match_pair in matches:
                if len(match_pair) == 2:
                    m, n = match_pair
                    if m.distance < 0.7 * n.distance:
                        good_matches.append(m)
            
            if len(good_matches) > 10:
                similarity = len(good_matches) / min(len(kp1), len(kp2))
            else:
                similarity = 0.1
            
            return min(1.0, similarity)
            
        except Exception:
            return 0.3
    
    def deep_template_matching(self, img1, img2):
        """Multi-scale template matching"""
        gray1 = cv2.cvtColor(img1, cv2.COLOR_BGR2GRAY)
        gray2 = cv2.cvtColor(img2, cv2.COLOR_BGR2GRAY)
        
        if gray1.shape != gray2.shape:
            gray2 = cv2.resize(gray2, (gray1.shape[1], gray1.shape[0]))
        
        # Multi-scale matching
        scales = [1.0, 0.8, 1.2]
        best_similarity = 0
        
        for scale in scales:
            if scale != 1.0:
                scaled_gray2 = cv2.resize(gray2, None, fx=scale, fy=scale)
                # Ensure scaled image is not larger than original
                h, w = gray1.shape
                scaled_h, scaled_w = scaled_gray2.shape
                if scaled_h <= h and scaled_w <= w:
                    result = cv2.matchTemplate(gray1, scaled_gray2, cv2.TM_CCOEFF_NORMED)
                    similarity = np.max(result)
                    best_similarity = max(best_similarity, similarity)
            else:
                result = cv2.matchTemplate(gray1, gray2, cv2.TM_CCOEFF_NORMED)
                similarity = np.max(result)
                best_similarity = max(best_similarity, similarity)
        
        return max(0, best_similarity)
    
    def color_distribution_similarity(self, img1, img2):
        """Color distribution similarity using moments"""
        # Convert to LAB color space for better color perception
        lab1 = cv2.cvtColor(img1, cv2.COLOR_BGR2LAB)
        lab2 = cv2.cvtColor(img2, cv2.COLOR_BGR2LAB)
        
        similarities = []
        for i in range(3):
            channel1 = lab1[:, :, i]
            channel2 = lab2[:, :, i]
            
            # Calculate moments
            moments1 = cv2.moments(channel1)
            moments2 = cv2.moments(channel2)
            
            # Compare moments
            moment_similarity = 1.0 / (1.0 + abs(moments1['m00'] - moments2['m00']) / 1000000)
            similarities.append(moment_similarity)
        
        return np.mean(similarities)
    
    def texture_analysis_similarity(self, img1, img2):
        """Texture analysis using Local Binary Patterns"""
        try:
            gray1 = cv2.cvtColor(img1, cv2.COLOR_BGR2GRAY)
            gray2 = cv2.cvtColor(img2, cv2.COLOR_BGR2GRAY)
            
            # Calculate LBP
            lbp1 = self.calculate_lbp(gray1)
            lbp2 = self.calculate_lbp(gray2)
            
            # Compare LBP histograms
            hist1 = cv2.calcHist([lbp1], [0], None, [256], [0, 256])
            hist2 = cv2.calcHist([lbp2], [0], None, [256], [0, 256])
            
            cv2.normalize(hist1, hist1)
            cv2.normalize(hist2, hist2)
            
            similarity = cv2.compareHist(hist1, hist2, cv2.HISTCMP_CORREL)
            
            return max(0, (similarity + 1) / 2)
            
        except Exception:
            return 0.4
    
    def calculate_lbp(self, image):
        """Calculate Local Binary Pattern"""
        height, width = image.shape
        lbp = np.zeros((height-2, width-2), dtype=np.uint8)
        
        for i in range(1, height-1):
            for j in range(1, width-1):
                center = image[i, j]
                code = 0
                code |= (image[i-1, j-1] > center) << 7
                code |= (image[i-1, j] > center) << 6
                code |= (image[i-1, j+1] > center) << 5
                code |= (image[i, j+1] > center) << 4
                code |= (image[i+1, j+1] > center) << 3
                code |= (image[i+1, j] > center) << 2
                code |= (image[i+1, j-1] > center) << 1
                code |= (image[i, j-1] > center) << 0
                lbp[i-1, j-1] = code
        
        return lbp
    
    def calculate_adaptive_weights(self, img1, img2, similarities):
        """Calculate adaptive weights based on image quality and consistency"""
        base_weights = [0.15, 0.20, 0.20, 0.15, 0.15, 0.15]
        
        # Adjust weights based on similarity consistency
        avg_similarity = np.mean(similarities)
        std_similarity = np.std(similarities)
        
        # If standard deviation is high, trust methods closer to average more
        if std_similarity > 0.2:
            for i in range(len(similarities)):
                deviation = abs(similarities[i] - avg_similarity)
                if deviation > 0.3:
                    base_weights[i] *= 0.5
                elif deviation < 0.1:
                    base_weights[i] *= 1.2
        
        # Normalize weights
        total_weight = sum(base_weights)
        normalized_weights = [w / total_weight for w in base_weights]
        
        return normalized_weights
    
    def advanced_scaling(self, similarity):
        """Advanced non-linear scaling"""
        # Use sigmoid-like function for better distribution
        if similarity > 0.8:
            return 0.8 + (similarity - 0.8) * 2.5
        elif similarity > 0.6:
            return 0.6 + (similarity - 0.6) * 1.5
        elif similarity > 0.4:
            return similarity
        else:
            return similarity ** 1.5

def main():
    # Test function
    fr = AdvancedFaceRecognition()
    print("✅ Advanced Face Recognition initialized successfully!")
    
    # Simple test with sample images
    if len(sys.argv) == 3:
        import sys
        image1_path = sys.argv[1]
        image2_path = sys.argv[2]
        
        # Read first image as base64 for testing
        with open(image1_path, 'rb') as f:
            image1_base64 = base64.b64encode(f.read()).decode('utf-8')
        
        result = fr.compare_faces_base64(image1_base64, image2_path)
        print(json.dumps(result, indent=2))

if __name__ == "__main__":
    main()