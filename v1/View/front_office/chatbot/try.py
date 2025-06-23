import base64
import os

def clean_img_data(data):
    """Removes the data:image/jpeg;base64, from the image data file"""
    data = data.split("base64,")[1]
    return data


def get_img_promt(filename="image_data.hiry"):
    if os.path.exists(filename):
        pass
    else:
        return False
    
    with open(filename, "r") as f:
        data = f.read()
        data = clean_img_data(data)
        return data.strip()

current_file_dir = os.path.dirname(os.path.realpath(__file__))

# Base64 string (example)
base64_string = get_img_promt(filename=f"{current_file_dir}/image_data.hiry")

# Specify the output image file path
output_file_path = current_file_dir+"/output_image.jpg"

# Decode the Base64 string
image_data = base64.b64decode(base64_string)

# Write the decoded bytes to an image file
with open(output_file_path, "wb") as image_file:
    image_file.write(image_data)

print(f"Image saved to {output_file_path}")