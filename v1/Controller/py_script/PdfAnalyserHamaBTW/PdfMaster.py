from io import BytesIO
import PyPDF2
import base64
import fitz
import json
import re
import os


class PdfMaster():
    def __init__(self, file_path: str = None):
        self.file_path = file_path
        self.pdf_data = None
        self.bin_data = None

        super().__init__()

    def set_file_path(self, file_path: str) -> None:
        self.file_path = file_path

    def set_bin_data(self, bin_data: str) -> None:
        self.bin_data = bin_data

    def read_pdf(self, file_path :str = None) -> str:
        if file_path == None:
            file_path = self.file_path
        
        if file_path == None:
            raise Exception("No file path was provided")
        
        with open(file_path, 'rb') as file:
            pdf_reader = PyPDF2.PdfReader(file)
            text = ''
            for page_num in range(len(pdf_reader.pages)):
                page = pdf_reader.pages[page_num]
                text += page.extract_text()
        self.pdf_data = text
        return text
    
    def read_pdf_binary(self, pdf_data):
        if pdf_data is None:
            raise ValueError("No PDF data provided")
        
        pdf_stream = BytesIO(pdf_data)
        pdf_reader = PyPDF2.PdfReader(pdf_stream)
        
        text = ''
        for page in pdf_reader.pages:
            text += page.extract_text()
        
        self.pdf_data = text
        return text
    
    def split_text_into_lines(self, text: str) -> list:
        lines = text.splitlines()
        return lines
    
    def find_substring_index(self, table: list, substring: str) -> int:
        for i, item in enumerate(table):
            if substring in item:
                return i
        return -1  
    
    def extract_phone_number(self, text: str) -> str | None :
        # Define the regex pattern to match phone numbers
        pattern = r'\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{2}[\s.-]?\d{2}'

        # Search for the pattern in the text
        match = re.search(pattern, text)

        # If a match is found, return the matched phone number
        if match:
            return match.group(0)
        else:
            return None  # Return None if no phone number is found

    def search_for_phone_nb(self, text: str) -> str | None:
        text = text.lower()
        data = self.split_text_into_lines(text)
        indx = self.find_substring_index(data, 'phone')
        phone_nb = self.extract_phone_number(data[indx])

        if phone_nb == None:
            #try methode 2
            for data_line in data:
                phone_nb = self.extract_phone_number(data_line)
                if phone_nb != None:
                    break


        return phone_nb

    def get_phone_nb(self):
        if self.pdf_data == None:
            raise Exception("No pdf data was provided")
        
        phone_nb = self.search_for_phone_nb(self.pdf_data)
        return phone_nb

    def get_pdf_data(self, lower=False):
        data = self.pdf_data
        if data == None:
            raise Exception("No pdf data was provided")
        
        if lower:
            data = data.lower()

        return data

    def extract_email_address(self, text: str) -> str | None:
        data = self.get_pdf_data()
        pattern = r'\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b'
        match = re.search(pattern, data)
        if match:
            return match.group(0)
        else:
            return None

    def search_for_email(self, text: str) -> str | None:
        text = text.lower()
        data = self.split_text_into_lines(text)
        indx = self.find_substring_index(data, 'mail')
        email = self.extract_email_address(data[indx])

        if email == None:
            #try methode 2
            for data_line in data:
                email = self.extract_email_address(data_line)
                if email != None:
                    break


        return email

    def get_email(self):
        if self.pdf_data == None:
            raise Exception("No pdf data was provided")
        
        email = self.search_for_email(self.pdf_data)
        return email
    
    def extract_address(self, text: str) -> str | None:
        # Find the index of the colon
        colon_index = text.find(':')
        
        # If colon is found, extract the address from the text after the colon
        if colon_index != -1:
            address = text[colon_index + 1:].strip()
            return address
        else:
            return text
    
    def search_for_address(self, text: str) -> str | None:
        text = text.lower()
        data = self.split_text_into_lines(text)
        indx = self.find_substring_index(data, 'address')
        address = self.extract_address(data[indx])

        if address is None:
            # Try another method if necessary
            pass

        return address
    
    def get_address(self) -> str | None:
        if self.pdf_data is None:
            raise Exception("No PDF data was provided")

        address = self.search_for_address(self.pdf_data)
        return address

    def search_for_skills(self, text: str) -> str | None:
        text = text.lower()
        data = self.split_text_into_lines(text)
        indx = self.find_substring_index(data, 'skill')
        skills = data[indx::]

        if skills is None:
            # Try another method if necessary
            pass

        return skills
    
    def get_skills(self) -> str | None:
        if self.pdf_data is None:
            raise Exception("No PDF data was provided")

        skills = self.search_for_skills(self.pdf_data)
        return skills

    def extract_pdf_with_formatting1(self, file_path):
        elements = []
        doc = fitz.open(file_path)
        for page_num in range(len(doc)):
            page = doc.load_page(page_num)
            for element in page.get_text("dict")["blocks"]:
                for line in element["lines"]:
                    for span in line["spans"]:
                        span_text = span["text"]
                        font_properties = span["font"]
                        color = span["color"]
                        # Check if the text is bold
                        is_bold = "bold" in font_properties.lower()
                        # Check if the text color is red
                        is_red = color == (1.0, 0.0, 0.0)
                        elements.append({
                            "text": span_text,
                            "is_bold": is_bold,
                            "is_red": is_red
                        })
        return elements
    
    def extract_pdf_with_formatting(self, file_path):
        elements = []
        doc = fitz.open(file_path)
        for page_num in range(len(doc)):
            page = doc.load_page(page_num)
            for element in page.get_text("dict")["blocks"]:
                for line in element["lines"]:
                    for span in line["spans"]:
                        span_text = span["text"]
                        font_properties = span["font"]
                        color = span["color"]
                        # # Check if the text is bold
                        # is_bold = "bold" in font_properties.lower()
                        # # Check if the text color is red
                        # is_red = color == (1.0, 0.0, 0.0)
                        elements.append({
                            "text": span_text,
                            "font": font_properties,
                            "color": color
                        })
        return elements

    def extract_pdf_with_formatting_binary(self, pdf_data: bytes):
        elements = []
        pdf_stream = BytesIO(pdf_data)
        doc = fitz.open(stream=pdf_stream)
        for page_num in range(len(doc)):
            page = doc.load_page(page_num)
            for element in page.get_text("dict")["blocks"]:
                for line in element["lines"]:
                    for span in line["spans"]:
                        span_text = span["text"]
                        font_properties = span["font"]
                        color = span["color"]
                        elements.append({
                            "text": span_text,
                            "font": font_properties,
                            "color": color
                        })
        return elements

    def find_substring_indexAdvanced(self, table: list, substring: str, bold : bool = False) -> int:
        if not bold:
            for i, item in enumerate(table):
                if substring in item['text'].lower():
                    return i
            return -1 

        else:
            for i, item in enumerate(table):
                if substring in item['text'].lower() and 'bold' in item['font'].lower():
                    return i
            return -1 

    def search_for_skillsAdvanced(self, file_path: str) -> str | None:
        if file_path == None:
            file_path = self.file_path
        
        if file_path == None:
            raise Exception("No file path was provided")
        
        data = self.extract_pdf_with_formatting(file_path)

        indx = self.find_substring_indexAdvanced(data, 'skill', bold=True)
        if indx == -1:
            indx = self.find_substring_indexAdvanced(data,'skill', bold=False)
        if indx == -1:
                return None
        
        skills_temp = data[indx::]

        skills = None
        if skills_temp != None and len(skills_temp) > 0:
            
            skills_tab = []
            skill_font = skills_temp[0]['font']
            if len(skills_temp) > 2:
                skill_next_font = skills_temp[1]['font']
                next_next_font = skills_temp[2]['font']
                if skill_font == skill_next_font and skill_next_font != next_next_font:
                    i = 0
                    while skills_temp[i]['font'] == skills_temp[i + 1]['font'] or skills_temp[i + 2]['font'] == next_next_font:
                        skills_tab.append(skills_temp[i]['text'])
                        i += 1
                    skills_tab.append(skills_temp[i+1]['text'])
                    skills = skills_tab
                else:
                    i = 1
                    while skills_temp[i]['font'] == skill_next_font:
                        skills_tab.append(skills_temp[i]['text'])
                        i += 1

                    skills = skills_tab     
            else:
                skills = skills_temp


        return skills
    
    def search_for_skillsAdvancedBinary(self, bin_data: bytes) -> str | None:
        
        data = self.extract_pdf_with_formatting_binary(bin_data)

        indx = self.find_substring_indexAdvanced(data, 'skill', bold=True)
        if indx == -1:
            indx = self.find_substring_indexAdvanced(data,'skill', bold=False)
        if indx == -1:
                return None
        
        skills_temp = data[indx::]

        skills = None
        if skills_temp != None and len(skills_temp) > 0:
            
            skills_tab = []
            skill_font = skills_temp[0]['font']
            if len(skills_temp) > 2:
                skill_next_font = skills_temp[1]['font']
                next_next_font = skills_temp[2]['font']
                if skill_font == skill_next_font and skill_next_font != next_next_font:
                    i = 0
                    while skills_temp[i]['font'] == skills_temp[i + 1]['font'] or skills_temp[i + 2]['font'] == next_next_font:
                        skills_tab.append(skills_temp[i]['text'])
                        i += 1
                    skills_tab.append(skills_temp[i+1]['text'])
                    skills = skills_tab
                else:
                    i = 1
                    while skills_temp[i]['font'] == skill_next_font:
                        skills_tab.append(skills_temp[i]['text'])
                        i += 1

                    skills = skills_tab     
            else:
                skills = skills_temp


        return skills
    
    def get_skillsAdvanced(self) -> str | None:
        skills = self.search_for_skillsAdvanced(self.file_path)
        final_data = []
        for skill in skills:
            data = skill.split(',')
            final_data = final_data + data
        
        final_skills = []
        for i in final_data:
            i = i.strip()
            if i != '' and (i.lower() != 'skill' and i.lower() != 'skills'):
                final_skills.append(i.replace(".",""))

        return final_skills

    def get_skillsAdvancedBinary(self) -> str | None:
        skills = self.search_for_skillsAdvancedBinary(self.bin_data)
        final_data = []
        for skill in skills:
            data = skill.split(',')
            final_data = final_data + data
        
        final_skills = []
        for i in final_data:
            i = i.strip()
            if i != '' and (i.lower() != 'skill' and i.lower() != 'skills'):
                final_skills.append(i.replace(".",""))

        return final_skills

    def pdf_to_binary(self, file_path):
        with open(file_path, 'rb') as file:
            binary_data = file.read()
        return binary_data

    def make_json(self, file_name: str, data: dict, path=None) -> None:
        if path is not None:
            file_path = f"{path}/{file_name}.json"
        else:
            file_path = f"{file_name}.json"

        with open(file_path, 'w') as json_file:
            json.dump(data, json_file, indent=4)
    
    def read_json(self, file_path):
        with open(file_path, 'r') as json_file:
            data = json.load(json_file)
        return data

    def convert_to_base64_then_to_text(self, binary_data: bytes) -> str:
        base64_data = base64.b64encode(binary_data)
        base64_data = base64_data.decode('utf-8')
        return base64_data

    def convert_base64_to_string_then_to_binary(self, base64_data: str) -> bytes:
        bytes_data = base64_data.encode('utf-8')
        base64_str = base64.b64encode(bytes_data).decode('utf-8')
        decoded_bytes = base64.b64decode(base64_str)
    
        return decoded_bytes