import flet as ft
import threading
import requests
import datetime
import pickle
import ollama
import json
import uuid
import os


class AiManager(ft.UserControl):
    """
    # an ai module based on ollama
    + an empty prompt is used to initialize the ai
    + by default the instance of this class will weak-up the ai
    """

    DEFAULT_AI_MODELS = ['llava', 'llama2-uncensored', 'gemma']
    DEFAULT_AI_MODEL = 'llava'
    DEFAULT_API_URL = 'http://localhost:11434/api/generate'
    DEFAULT_HEADERS = {'content-type': 'application/json'}
    
    DEFAULT_FOLDER_PATH = "./conversations/"

    def __init__(self, page, ai_models=None, ai_model=DEFAULT_AI_MODEL, api_url=DEFAULT_API_URL, headers=DEFAULT_HEADERS, folder_path=DEFAULT_FOLDER_PATH, convs_title_handler=None):
        
        super().__init__()

        self.page = page

        if ai_models == None:
            ai_models = self.get_available_models()

        self.ai_models = ai_models
        if type(ai_models) != list:
            self.ai_models = self.DEFAULT_AI_MODELS


        self.ai_model = ai_model
        if (self.ai_model not in self.ai_models):
            self.ai_model = self.DEFAULT_AI_MODEL
        
        self.api_url = api_url
        if type(api_url) != str:
            self.api_url = self.DEFAULT_API_URL
        
        self.headers = headers
        if type(headers) != dict:
            self.headers = self.DEFAULT_HEADERS
        
        self.folder_path = folder_path
        if type(folder_path) != str:
            self.folder_path = self.DEFAULT_FOLDER_PATH

        self.convs_title_handler = convs_title_handler

        self.prompt_context = []
        self.current_conversation_data = None
        self.current_conversation_file_path = None
        self.generated_title = None
        
        self.current_conversation_title = None
        self.current_conversation_title_frame = None

        # Check if the directory exists, if not, create it
        self.create_the_conversations_folder(self.folder_path)

        #self.weak_up()
    
    def create_the_conversations_folder(self, save_path):
        directory = os.path.dirname(save_path)
        if not os.path.exists(directory):
            os.makedirs(directory)
            return True
        
        return False

    def check_if_conversation_exist(self, file_path):
        return os.path.exists(file_path)

    def fix_the_conversation_title(self, conversation_title):
        return "".join(c if c.isalnum() or c in "._-" else "_" for c in conversation_title)

    def prepare_data_for_the_prompt(self, prompt: str, model: str | None = None, stream: bool | None = False, context: list | None = None, images: list | None = None) -> dict | None:
        
        try:
            prompt = str(prompt)
        except:
            pass

        
        if type(prompt) == str :
            
            if model == None:
                model = self.ai_model
            
            # an empty prompt is used to initialize the ai
            if prompt.replace(' ', '') == "":
                data = {
                    'model': model
                }
            
            else:
                data = {
                    'model': model,
                    "stream": stream,
                    'prompt': prompt,
                }
            
            if context == None:
                context = self.prompt_context
            
            if context != None and type(context) == list and len(context) > 0:
                data['context'] = context
                self.prompt_context = context
            
            if images != None and type(images) == list and len(images) > 0:
                data['images'] = images
            
            return data
        
        else:
            return None

    def send_the_prompt(self, prompt: str = "", model: str | None = None, stream: bool | None = False, context: list | None = None, images: list | None = None) -> dict | None:

        data = self.prepare_data_for_the_prompt(prompt, model, stream, context, images)

        if data != None:
            response = requests.post(self.api_url, headers=self.headers, data=json.dumps(data))

            if response.status_code == 200:
                response_txt = response.text
                returnd_data = json.loads(response_txt)
                # res = data['response']
                # context = data['context']

                data_with_prompt = {'promte':data['prompt'], 'data got':returnd_data}

                return data_with_prompt

            else:
                print("Error:", response.status_code, response.text)
                return None

    def make_a_prompt(self, prompt: str = "", model: str | None = None, stream: bool | None = False, context: list | None = None, images: list | None = None, keep_record: bool = True) -> dict | None:

        #if there is no previous conv loaded make a new 1
        if self.current_conversation_data == None:
            self.make_a_new_conversation()

        res = self.send_the_prompt(prompt, model, stream, context, images)
        
        if keep_record:
            current_prompt = {'content':res['promte'], 'sender':'user'}
        else:
            current_prompt = {'content':res['promte'], 'sender':'auto'}

        
        # add the question
        self.update_a_conversation(self.current_conversation_file_path, question=current_prompt)

        data = res['data got']

        if data != None:
            if keep_record:
                prompt_response = {'content':data['response'], 'sender':'bot'}
            else:
                prompt_response = {'content':data['response'], 'sender':'auto'}

            #prompt_response = data['response']
            
            if prompt_response != "":
                prompt_context = data['context']
                
                # add the context and the answer and update the prompts nb
                
                self.update_a_conversation(self.current_conversation_file_path, answer=prompt_response, context=prompt_context, add_a_prompt_to_the_nb=True)
                self.convs_title_handler.chat_manager.remove_thinking_chat_msgs()
                if prompt_response['sender'] != "auto":
                    self.convs_title_handler.chat_manager.add_chat_msgs('Bot', prompt_response['content'])

                returnd_data = {'response' :prompt_response, 'context':self.prompt_context}

                # if its the first prompt (prompts nb = 1) ask for a title:
                if self.current_conversation_data['prompts nb'] == 1:
                    print("generating title")
                    self.apply_title_to_the_conversation()

                return returnd_data
    
        #print(data)
        return {'response' :'', 'context':None}

    def weak_up(self):
        data = self.prepare_data_for_the_prompt("")

        response = requests.post(self.api_url, headers=self.headers, data=json.dumps(data))

        if response.status_code == 200:
            self.reset_to_default()
            print("ready")
            return True

        else:
            print("Error:", response.status_code, response.text)
            return False

    def reset_to_default(self):
        print("i got reseted")

        self.prompt_context = []
        self.current_conversation_data = None
        self.current_conversation_file_path = None
        self.generated_title = None
        
        self.current_conversation_title = None
        self.current_conversation_title_frame = None

        # Check if the directory exists, if not, create it
        self.create_the_conversations_folder(self.folder_path)

    def set_context(self, new_context):
        self.prompt_context = new_context

    def generate_conversation_title(self, using_ai=False):
        
        if using_ai and len(self.prompt_context) > 0:
            data = self.make_a_prompt("give this conversation a short title", keep_record=False)
            return data['response']['content']
        
        else:

            timestamp = datetime.datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
            unique_id = str(uuid.uuid4())[:8]  # Using the first 8 characters of UUID
            title = f"conversation_{timestamp}_{unique_id}.hbtwai"
            
            return title

    def apply_title_to_the_conversation(self):

        # Define the target function
        def target_function(event):
            title = self.generate_conversation_title(using_ai=True)
            # Set the event to signal that the function is done
            event.set()
            # Store the result in the instance variable
            self.generated_title = title

        # Define an event to signal the completion of the target function
        event = threading.Event()

        # Define a variable to store the result of the target function
        # already done in the init

        # Create a thread with the target function as the target
        first_thread = threading.Thread(target=target_function, args=(event,))

        # Start the first thread
        first_thread.start()

        # Wait for the event to be set (i.e., for the target function to complete)
        event.wait()

        # Now you can access the generated title using self.generated_title
        print("Generated conversation title:", self.generated_title[2:-2])
        self.convs_title_handler.update_selected_conv_title(self.generated_title[2:-2])
        
    def make_a_new_conversation(self, save_path=None, title=None):
        
        if save_path == None:
            save_path = self.folder_path
        
        # making the title
        if title == None:
            title = self.generate_conversation_title()
        
        # Check if the directory exists, if not, create it
        self.create_the_conversations_folder(save_path)

        # creating the title (sanitized)
        sanitized_title = self.fix_the_conversation_title(title)

        #creating the file path
        file_path = os.path.join(save_path, sanitized_title)

        # check if conversation exists in the folder path
        if not self.check_if_conversation_exist(file_path):
            
            data = {
                'conversation file title' : title, 
                'conversation title': None, 
                'context':[], 
                'questions':[], 
                'answers':[],
                'prompts nb': 0,
            }

            with open(file_path, 'wb') as f:
                pickle.dump(data, f)

            # reset the ai
            self.reset_to_default()

            # set the data after the reset
            self.current_conversation_data = data
            self.current_conversation_file_path = file_path

            return True
        
        return False

    def load_a_conversation(self, file_title=None, save_path=None):
        
        if file_title == None:
            file_title = self.current_conversation_file_path

        if save_path == None:
            save_path = self.folder_path
                
        # Check if the directory exists, if not, create it
        if self.create_the_conversations_folder(save_path):
            # can't load the conv cz the folder doesn't exist
            print("folder created")
            return False

        #creating the file path
        file_path = os.path.join(save_path,  file_title)

        # check if conversation exists in the folder path
        if self.check_if_conversation_exist(file_path):

            try:
                with open(file_path, 'rb') as f:
                    data = pickle.load(f)

                self.set_context(data['context'])

                # set the data
                self.current_conversation_data = data
                self.current_conversation_file_path = file_path

                print(data)

                return True
            
            except:
                return False
        
        else:
            print("can't find the conversation")
            return False

    def update_a_conversation(self, file_path=None, context=None, conv_title=None, question=None, answer=None, add_a_prompt_to_the_nb=False):
        
        if file_path == None:
            file_path = self.current_conversation_file_path

        # check if conversation exists in the folder path
        if self.check_if_conversation_exist(file_path):

            try:
                with open(file_path, 'rb') as f:
                    data = pickle.load(f)
                
                # update context add new context to previous
                if context != None:
                    current_context = data['context']
                    new_context = current_context + context
                    data['context'] = new_context
                
                # change conv title
                if conv_title != None:
                    data['conversation title'] = conv_title
                
                # update questions add new question to previous
                if question != None:
                    current_questions = data['questions']
                    new_questions = current_questions + [question]
                    data['questions'] = new_questions
                
                # update answers add new answers to previous
                if answer != None:
                    current_answers = data['answers']
                    new_answers = current_answers + [answer]
                    data['answers'] = new_answers
                
                # update the prompts nb
                if add_a_prompt_to_the_nb:
                    current_prompts_nb = data['prompts nb']
                    new_prompts_nb = current_prompts_nb + 1
                    data['prompts nb'] = new_prompts_nb


                self.set_context(data['context'])

                # set the data
                self.current_conversation_data = data

                with open(file_path, 'wb') as f:
                    pickle.dump(data, f)

                return True
            
            except:
                return False
        
        else:
            return False

    def replace_a_conversation_data(self, data, file_path=None):
        if file_path == None:
            file_path = self.current_conversation_file_path

        # check if conversation exists in the folder path
        if self.check_if_conversation_exist(file_path):

            try:
                with open(file_path, 'wb') as f:
                    pickle.dump(data, f)
                
                self.current_conversation_data = data

                return True
            
            except:
                return False

    def load_a_conversation_data(self, file_path):
        
        # check if conversation exists in the folder path
        if self.check_if_conversation_exist(file_path):

            try:
                with open(file_path, 'rb') as f:
                    data = pickle.load(f)

                return data
            
            except:
                return None
        
        else:
            print("can't find the conversation")
            return None

    def get_available_models(self):
        try:
            data = ollama.list()['models']
            models = []

            for row in data:
                current_model = row['name']
                try:
                    current_model = current_model.split(':')[0]
                except:
                    pass

                models.append(current_model)
            
            return models
        except:
            return None
    
    def set_conversation_data(self, new_data):
        self.current_conversation_data = new_data

    def get_conversation_data(self):
        return self.current_conversation_data

    def set_current_conversation_title(self, new_title):
        self.current_conversation_title = new_title
    
    def get_current_conversation_title(self):
        return self.current_conversation_title

    def set_convs_title_holder(self, new_val):
        self.convs_title_handler = new_val
    
    def get_convs_title_holder(self):
        return self.convs_title_handler

# ai = AiManager()

# ai.weak_up()
# data = ai.make_a_prompt("tell me a joke")
# print(data['response'], "\n")
# cont = data['context']
# data = ai.make_a_prompt("what did i ask u last time")
# print(data['response'], "\n")
# ai.reset_to_default()
# #print(ai.prompt_context)
# data = ai.make_a_prompt("what did i ask u last time")
# print(data['response'], "\n")
# #print(ai.prompt_context)
# ai.set_context(cont)
# #print(ai.prompt_context)
# data = ai.make_a_prompt("what did i ask u last time")
# print(data['response'], "\n")
# print("done")

# print()
# print()
# print()

# ai.weak_up()
# data = ai.make_a_prompt("tell me a joke")
# print(data['response'], "\n")
# data = ai.make_a_prompt("give this conversation a short title")
# print(data['response'], "\n")


# ai = AiManager()
# d = ai.make_a_prompt("tell me a joke")
# print(d)
# print(ai.generate_conversation_title(True))
            
# ai = AiManager()
# ai.get_available_models()

