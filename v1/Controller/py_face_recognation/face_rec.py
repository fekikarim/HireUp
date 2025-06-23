import face_recognition
import cv2
import numpy as np
import os

def remove_extension(file_name):
    return os.path.splitext(file_name)[0]

def list_files_in_folder(folder_path):
    files = os.listdir(folder_path)
    return files
 



current_file_dir = os.path.dirname(os.path.realpath(__file__))

window_title = "hireUp Face Recognation"

video_capture = cv2.VideoCapture(0)

files_list = list_files_in_folder(f'{current_file_dir}/photos')

known_face_encoding = []
known_faces_names = []

for file in files_list:
    file_name_no_extention = remove_extension(file)
    file_path = f'{current_file_dir}/photos/{file}'
    face_image = face_recognition.load_image_file(file_path)
    face_image_encoding = face_recognition.face_encodings(face_image)[0]
    known_face_encoding.append(face_image_encoding)
    known_faces_names.append(file_name_no_extention)
 
# jobs_image = face_recognition.load_image_file("photos/jobs.jpg")
# jobs_encoding = face_recognition.face_encodings(jobs_image)[0]
 
# ratan_tata_image = face_recognition.load_image_file("photos/tata.jpg")
# ratan_tata_encoding = face_recognition.face_encodings(ratan_tata_image)[0]
 
# sadmona_image = face_recognition.load_image_file("photos/sadmona.jpg")
# sadmona_encoding = face_recognition.face_encodings(sadmona_image)[0]
 
# tesla_image = face_recognition.load_image_file("photos/tesla.jpg")
# tesla_encoding = face_recognition.face_encodings(tesla_image)[0]
 
# known_face_encoding = [
# jobs_encoding,
# ratan_tata_encoding,
# sadmona_encoding,
# tesla_encoding
# ]
 
# known_faces_names = [
# "jobs",
# "ratan tata",
# "sadmona",
# "tesla"
# ]
 
students = known_faces_names.copy()
 
face_locations = []
face_encodings = []
face_names = []
s=True

programe_on = True

while programe_on:
    _,frame = video_capture.read()
    small_frame = cv2.resize(frame,(0,0),fx=0.25,fy=0.25)
    #rgb_small_frame = small_frame[:,:,::-1]
    rgb_small_frame = np.ascontiguousarray(small_frame[:, :, ::-1])
    if s:
        face_locations = face_recognition.face_locations(rgb_small_frame)
        face_encodings = face_recognition.face_encodings(rgb_small_frame,face_locations)
        face_names = []
        for face_encoding in face_encodings:
            matches = face_recognition.compare_faces(known_face_encoding,face_encoding)
            name=""
            face_distance = face_recognition.face_distance(known_face_encoding,face_encoding)
            best_match_index = np.argmin(face_distance)
            if matches[best_match_index]:
                name = known_faces_names[best_match_index]
 
            face_names.append(name)
            if name in known_faces_names:
                font = cv2.FONT_HERSHEY_SIMPLEX
                bottomLeftCornerOfText = (10,100)
                fontScale              = 1.5
                fontColor              = (255,0,0)
                thickness              = 3
                lineType               = 2
 
                cv2.putText(frame,name+' Present', 
                    bottomLeftCornerOfText, 
                    font, 
                    fontScale,
                    fontColor,
                    thickness,
                    lineType)
 
                if name in students:
                    print(f"detected : {name}")
                    #close the programe
                    programe_on = False

                    # students.remove(name)
                    # print(students)
                    
    
    cv2.imshow(f'{window_title}',frame)
    
    if cv2.waitKey(1) & 0xFF == ord('q') or cv2.getWindowProperty(f'{window_title}', cv2.WND_PROP_VISIBLE) < 1:
        print("closed")
        programe_on = False
 
video_capture.release()
cv2.destroyAllWindows()