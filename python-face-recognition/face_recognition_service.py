import cv2
import face_recognition
import numpy as np
import os
import sys
import json
from PIL import Image
import base64
import io

class FaceRecognition:
    def __init__(self):
        self.tolerance = 0.6
        
    def compare_faces_base64(self, image1_base64, image2_path):
        """
        Compare base64 image with file image
        """
        try:
            print("Starting Python Face Recognition...")
            
            # Decode base64 image
            if ',' in image1_base64:
                image1_base64 = image1_base64.split(',')[1]
                
            image1_data = base64.b64decode(image1_base64)
            image1 = Image.open(io.BytesIO(image1_data))
            
            # Convert to RGB if necessary
            if image1.mode != 'RGB':
                image1 = image1.convert('RGB')
                
            image1_np = np.array(image1)
            
            print("Base64 image decoded successfully")
            
            # Load second image
            if not os.path.exists(image2_path):
                return {
                    "success": False, 
                    "error": f"Profile image not found: {image2_path}"
                }
                
            image2 = face_recognition.load_image_file(image2_path)
            print("Profile image loaded successfully")
            
            # Find face encodings
            face_encodings1 = face_recognition.face_encodings(image1_np)
            face_encodings2 = face_recognition.face_encodings(image2)
            
            print(f"Faces detected - Captured: {len(face_encodings1)}, Profile: {len(face_encodings2)}")
            
            # Check if faces are detected
            if len(face_encodings1) == 0:
                return {
                    "success": False, 
                    "error": "No face detected in captured image",
                    "faces_detected_captured": len(face_encodings1),
                    "faces_detected_profile": len(face_encodings2)
                }
                
            if len(face_encodings2) == 0:
                return {
                    "success": False, 
                    "error": "No face detected in profile image",
                    "faces_detected_captured": len(face_encodings1),
                    "faces_detected_profile": len(face_encodings2)
                }
            
            # Get the first face encoding from each image
            encoding1 = face_encodings1[0]
            encoding2 = face_encodings2[0]
            
            # Calculate face distance
            face_distance = face_recognition.face_distance([encoding1], encoding2)[0]
            
            # Convert distance to similarity percentage
            similarity = max(0, (1 - face_distance) * 100)
            
            print(f"Python Face Recognition Result - Distance: {face_distance}, Similarity: {similarity}%")
            
            return {
                "success": True,
                "similarity": round(similarity, 2),
                "face_distance": round(face_distance, 4),
                "faces_detected_captured": len(face_encodings1),
                "faces_detected_profile": len(face_encodings2),
                "method": "python_face_recognition"
            }
            
        except Exception as e:
            print(f"Error in Python face recognition: {str(e)}")
            return {
                "success": False, 
                "error": f"Python Face Recognition error: {str(e)}"
            }

def main():
    # Test function
    if len(sys.argv) == 3:
        image1_path = sys.argv[1]
        image2_path = sys.argv[2]
        
        fr = FaceRecognition()
        
        # Read first image as base64 for testing
        with open(image1_path, 'rb') as f:
            image1_base64 = base64.b64encode(f.read()).decode('utf-8')
        
        result = fr.compare_faces_base64(image1_base64, image2_path)
        print(json.dumps(result))

if __name__ == "__main__":
    main()