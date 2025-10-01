@echo off
echo Starting Python Face Recognition API...
cd /d "C:\laragon\www\Toko Buku\Facerecog\python-face-recognition"
call venv\Scripts\activate
python face_api.py
pause