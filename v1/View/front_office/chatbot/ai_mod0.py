from AiManagerHamaBTW import AiManager

import sys

ai = AiManager(page=None, ai_model='llama2-uncensored')

# Access the passed parameter as command-line argument
if len(sys.argv) > 1:
    param_value = sys.argv[1]

    prompt = param_value
    rep = ai.send_the_prompt(prompt)
    try:
        print(rep['data got']['response'])
    except:
        print("error fetching the response")
    
else:
    print("No parameter received")

