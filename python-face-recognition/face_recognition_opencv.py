import cv2
import numpy as np
from PIL import Image
import base64
import io
import os
import json

class FaceRecognitionOpenCV:
    def __init__(self):
        # Load face detection classifiers
        self.face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
        self.eye_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_eye.xml')
        
    def compare_faces_base64(self, image1_base64, image2_path):
        """
        Compare faces using OpenCV with enhanced accuracy
        """
        try:
            print("🚀 Starting OpenCV Face Comparison...")
            
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
            
            # Preprocess images for better face detection
            image1_processed = self.preprocess_image(image1_cv)
            image2_processed = self.preprocess_image(image2_cv)
            
            # Detect faces with enhanced parameters
            faces1 = self.detect_faces_enhanced(image1_processed)
            faces2 = self.detect_faces_enhanced(image2_processed)
            
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
            
            # Get the best face from each image
            face1_region = self.get_face_region(image1_cv, faces1[0])
            face2_region = self.get_face_region(image2_cv, faces2[0])
            
            # Calculate similarity using multiple advanced methods
            similarity = self.calculate_advanced_similarity(face1_region, face2_region)
            
            print(f"📊 Similarity calculated: {similarity}%")
            
            return {
                "success": True,
                "similarity": round(similarity, 2),
                "faces_detected_captured": len(faces1),
                "faces_detected_profile": len(faces2),
                "method": "opencv_enhanced"
            }
            
        except Exception as e:
            print(f"❌ Error in face comparison: {str(e)}")
            return {
                "success": False, 
                "error": f"Comparison error: {str(e)}"
            }
    
    def preprocess_image(self, image):
        """Preprocess image for better face detection"""
        # Convert to grayscale
        gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
        
        # Enhance contrast using CLAHE
        clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8,8))
        enhanced = clahe.apply(gray)
        
        # Apply Gaussian blur to reduce noise
        blurred = cv2.GaussianBlur(enhanced, (5, 5), 0)
        
        return blurred
    
    def detect_faces_enhanced(self, image):
        """Enhanced face detection with multiple parameters"""
        # Try different scale factors and min neighbors
        faces = self.face_cascade.detectMultiScale(
            image,
            scaleFactor=1.1,
            minNeighbors=5,
            minSize=(30, 30),
            flags=cv2.CASCADE_SCALE_IMAGE
        )
        
        # If no faces found, try with different parameters
        if len(faces) == 0:
            faces = self.face_cascade.detectMultiScale(
                image,
                scaleFactor=1.05,
                minNeighbors=3,
                minSize=(20, 20),
                flags=cv2.CASCADE_SCALE_IMAGE
            )
        
        return faces
    
    def get_face_region(self, image, face_coords):
        """Extract and align face region"""
        x, y, w, h = face_coords
        
        # Expand face region slightly
        expand_x = int(w * 0.1)
        expand_y = int(h * 0.1)
        
        x = max(0, x - expand_x)
        y = max(0, y - expand_y)
        w = min(image.shape[1] - x, w + 2 * expand_x)
        h = min(image.shape[0] - y, h + 2 * expand_y)
        
        face_region = image[y:y+h, x:x+w]
        
        # Resize to standard size
        face_resized = cv2.resize(face_region, (200, 200))
        
        return face_resized
    
    def calculate_advanced_similarity(self, face1, face2):
        """Calculate similarity using multiple advanced methods"""
        try:
            # Method 1: Histogram Comparison (Color)
            hist_similarity = self.histogram_similarity(face1, face2)
            
            # Method 2: Structural Similarity
            structural_similarity = self.structural_similarity(face1, face2)
            
            # Method 3: Feature-based Similarity
            feature_similarity = self.feature_based_similarity(face1, face2)
            
            # Method 4: Template Matching
            template_similarity = self.template_matching_similarity(face1, face2)
            
            # Weighted combination of all methods
            weights = [0.25, 0.30, 0.25, 0.20]  # Adjust weights based on importance
            similarities = [hist_similarity, structural_similarity, feature_similarity, template_similarity]
            
            final_similarity = sum(s * w for s, w in zip(similarities, weights))
            
            # Apply non-linear scaling to make differences more noticeable
            scaled_similarity = self.apply_scaling(final_similarity)
            
            return min(100, scaled_similarity * 100)
            
        except Exception as e:
            print(f"Error in similarity calculation: {e}")
            return 0
    
    def histogram_similarity(self, img1, img2):
        """Compare color histograms"""
        # Calculate histograms for each channel
        hist1 = cv2.calcHist([img1], [0, 1, 2], None, [8, 8, 8], [0, 256, 0, 256, 0, 256])
        hist2 = cv2.calcHist([img2], [0, 1, 2], None, [8, 8, 8], [0, 256, 0, 256, 0, 256])
        
        # Normalize histograms
        cv2.normalize(hist1, hist1)
        cv2.normalize(hist2, hist2)
        
        # Compare using correlation
        similarity = cv2.compareHist(hist1, hist2, cv2.HISTCMP_CORREL)
        
        return max(0, (similarity + 1) / 2)  # Convert to 0-1 range
    
    def structural_similarity(self, img1, img2):
        """Calculate structural similarity"""
        # Convert to grayscale
        gray1 = cv2.cvtColor(img1, cv2.COLOR_BGR2GRAY)
        gray2 = cv2.cvtColor(img2, cv2.COLOR_BGR2GRAY)
        
        # Calculate MSE
        mse = np.mean((gray1 - gray2) ** 2)
        if mse == 0:
            return 1.0
        
        # Calculate PSNR-based similarity
        max_pixel = 255.0
        psnr = 20 * np.log10(max_pixel / np.sqrt(mse))
        
        # Convert PSNR to similarity score (0-1)
        similarity = min(1.0, psnr / 50)
        
        return similarity
    
    def feature_based_similarity(self, img1, img2):
        """Feature-based similarity using ORB features"""
        try:
            # Initialize ORB detector
            orb = cv2.ORB_create()
            
            # Find keypoints and descriptors
            kp1, des1 = orb.detectAndCompute(img1, None)
            kp2, des2 = orb.detectAndCompute(img2, None)
            
            if des1 is None or des2 is None:
                return 0.5  # Return neutral score if no features found
            
            # BFMatcher with default params
            bf = cv2.BFMatcher(cv2.NORM_HAMMING, crossCheck=True)
            
            # Match descriptors
            matches = bf.match(des1, des2)
            
            # Calculate similarity based on match distances
            if len(matches) > 0:
                distances = [m.distance for m in matches]
                avg_distance = np.mean(distances)
                
                # Convert distance to similarity (lower distance = higher similarity)
                similarity = 1.0 - (avg_distance / 100)  # Normalize
                return max(0, min(1, similarity))
            else:
                return 0.3  # Low similarity if no matches
                
        except Exception:
            return 0.5  # Return neutral score on error
    
    def template_matching_similarity(self, img1, img2):
        """Template matching similarity"""
        # Convert to grayscale
        gray1 = cv2.cvtColor(img1, cv2.COLOR_BGR2GRAY)
        gray2 = cv2.cvtColor(img2, cv2.COLOR_BGR2GRAY)
        
        # Resize to same size if different
        if gray1.shape != gray2.shape:
            gray2 = cv2.resize(gray2, (gray1.shape[1], gray1.shape[0]))
        
        # Perform template matching
        result = cv2.matchTemplate(gray1, gray2, cv2.TM_CCOEFF_NORMED)
        similarity = np.max(result)
        
        return max(0, similarity)
    
    def apply_scaling(self, similarity):
        """Apply non-linear scaling to emphasize differences"""
        # Use exponential scaling to make high similarities even higher
        if similarity > 0.7:
            return similarity ** 0.8  # Makes high values even higher
        else:
            return similarity ** 1.2  # Makes low values even lower

def main():
    # Test function
    fr = FaceRecognitionOpenCV()
    print("✅ OpenCV Face Recognition initialized successfully!")
    
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