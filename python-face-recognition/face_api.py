from flask import Flask, request, jsonify
import logging
import os

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Import advanced face recognition
from face_recognition_advanced import AdvancedFaceRecognition

app = Flask(__name__)

# Enable CORS manually
@app.after_request
def after_request(response):
    response.headers.add('Access-Control-Allow-Origin', '*')
    response.headers.add('Access-Control-Allow-Headers', 'Content-Type,Authorization')
    response.headers.add('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS')
    return response

# Initialize advanced face recognition
face_recog = AdvancedFaceRecognition()

@app.route('/api/compare-faces', methods=['POST', 'OPTIONS'])
def compare_faces():
    if request.method == 'OPTIONS':
        return '', 200
        
    try:
        logger.info("🎯 Received face comparison request from Laravel")
        
        data = request.json
        
        if not data:
            return jsonify({
                "success": False,
                "error": "No JSON data received"
            }), 400
        
        if 'image1' not in data or 'image2_path' not in data:
            return jsonify({
                "success": False,
                "error": "Missing required parameters: image1 (base64) and image2_path"
            }), 400
        
        image1_base64 = data['image1']
        image2_path = data['image2_path']
        
        logger.info(f"📁 Comparing with profile image: {image2_path}")
        
        # Check if profile image exists
        if not os.path.exists(image2_path):
            return jsonify({
                "success": False,
                "error": f"Profile image not found: {image2_path}"
            }), 404
        
        result = face_recog.compare_faces_base64(image1_base64, image2_path)
        
        logger.info(f"📊 Advanced comparison result: {result}")
        
        return jsonify(result)
        
    except Exception as e:
        logger.error(f"❌ Error in compare-faces endpoint: {str(e)}")
        return jsonify({
            "success": False,
            "error": f"Advanced Python API error: {str(e)}"
        }), 500

@app.route('/api/health', methods=['GET'])
def health_check():
    return jsonify({
        "status": "healthy", 
        "service": "Advanced Python Face Recognition API",
        "version": "2.0.0",
        "method": "Advanced OpenCV with 6 algorithms + adaptive weighting"
    })

@app.route('/')
def home():
    return jsonify({
        "message": "🚀 Advanced Python Face Recognition API is running!",
        "features": [
            "Multi-stage face detection",
            "6 different similarity algorithms",
            "Adaptive weighting based on image quality",
            "Advanced preprocessing",
            "Texture analysis with LBP",
            "Color distribution analysis",
            "Multi-scale template matching"
        ],
        "endpoints": {
            "POST /api/compare-faces": "Compare base64 image with file",
            "GET /api/health": "Health check"
        }
    })

if __name__ == '__main__':
    logger.info("🚀 Starting Advanced Python Face Recognition API on http://127.0.0.1:5000")
    logger.info("📊 Using Advanced OpenCV with 6 similarity algorithms + adaptive weighting")
    app.run(host='127.0.0.1', port=5000, debug=True)