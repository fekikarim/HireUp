from AiManagerHamaBTW import AiManager

import os

def get_promt(filename="data.hiry"):
    if os.path.exists(filename):
        pass
    else:
        return False
    
    with open(filename, "r") as f:
        data = f.read()
        return data.strip()



current_file_dir = os.path.dirname(os.path.realpath(__file__))

ai = AiManager(page=None, ai_model='llama2-uncensored')

param_value = get_promt()

if param_value == False:
    print("error fetching the response")
    exit()

prompt = param_value
rep = ai.send_the_prompt(prompt)
try:
    print(rep['data got']['response'])
except:
    print("error fetching the response")
    

