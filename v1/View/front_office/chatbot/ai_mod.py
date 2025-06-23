from AiManagerHamaBTW import AiManager

import os

def clean_img_data(data):
    """Removes the data:image/jpeg;base64, from the image data file"""
    data = data.split("base64,")[1]
    return data


def get_promt(filename):
    if os.path.exists(filename):
        pass
    else:
        return False
    
    with open(filename, "r") as f:
        data = f.read()
        return data.strip()

def get_img_promt(filename):
    if os.path.exists(filename):
        pass
    else:
        return False
    
    with open(filename, "r") as f:
        data = f.read()
        data = clean_img_data(data)
        return data.strip()

def delete_data_file(file_path):
    if os.path.exists(file_path):
        # Delete the file
        os.remove(file_path)
        return True
    else:
        return False


current_file_dir = os.path.dirname(os.path.realpath(__file__))

#ai = AiManager(page=None, ai_model='llama2-uncensored')
ai = AiManager(page=None, ai_model='llava')
context = [733, 16289, 28793, 6312, 28709, 368, 460, 345, 22590, 28724, 28739, 264, 10706, 12435, 486, 382, 536, 2738, 28725, 382, 536, 2738, 349, 264, 2496, 304, 264, 4400, 1307, 298, 15270, 757, 14095, 579, 590, 541, 1300, 7794, 871, 3859, 486, 347, 28723, 1687, 272, 3057, 2496, 28725, 304, 390, 613, 773, 513, 3637, 24057, 873, 693, 460, 28725, 714, 574, 345, 22590, 28724, 28739, 272, 10706, 12435, 388, 385, 5682, 298, 1316, 2188, 304, 5055, 1126, 438, 6790, 9796, 368, 541, 3084, 2057, 294, 409, 304, 1316, 706, 28725, 332, 541, 11630, 5844, 28713, 486, 4865, 272, 4256, 28712, 18128, 28713, 297, 378, 332, 541, 20765, 605, 9743, 304, 6593, 706, 332, 541, 10148, 331, 8310, 579, 513, 3637, 12373, 574, 544, 302, 369, 28725, 640, 3290, 573, 28804, 733, 28748, 16289, 28793, 6756, 28709, 28808, 315, 28742, 28719, 24384, 28724, 28725, 264, 10706, 10093, 5682, 298, 6031, 5443, 304, 5055, 1126, 395, 6790, 9796, 28723, 1136, 264, 8252, 13892, 3859, 486, 382, 536, 2738, 28725, 264, 2496, 2841, 3864, 297, 2389, 21349, 28725, 315, 14612, 272, 5537, 298, 20765, 10181, 1259, 390, 605, 9743, 1413, 1186, 28754, 14760, 28725, 3084, 7478, 356, 4118, 9760, 28725, 304, 1019, 1316, 5443, 297, 10148, 14508, 8310, 28723, 1984, 6258, 5541, 349, 298, 5407, 369, 3376, 659, 264, 17364, 2659, 1312, 27555, 1077, 272, 382, 536, 2738, 5181, 28723, 1047, 368, 927, 707, 11611, 442, 506, 707, 4224, 28725, 1601, 1933, 298, 1460, 28808, 28705]
ai.set_context(context)

param_value = get_promt(filename=f"{current_file_dir}/data.hiry")
img_value = get_img_promt(filename=f"{current_file_dir}/image_data.hiry")

if param_value == False:
    print("error fetching the response")
    delete_data_file("data.hiry")
    delete_data_file("image_data.hiry")
    exit()

prompt = param_value
img_prompt = img_value
if img_prompt == False:
    rep = ai.send_the_prompt(prompt)
else:
    rep = ai.send_the_prompt(prompt, images=[img_prompt])
try:
    print(rep['data got']['response'])
    delete_data_file("data.hiry")
    delete_data_file("image_data.hiry")
except:
    print("error fetching the response")
    delete_data_file("data.hiry")
    delete_data_file("image_data.hiry")
    

