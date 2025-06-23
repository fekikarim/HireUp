from PdfAnalyserHamaBTW.PdfMaster import PdfMaster
import base64
import sys
import os

file_path = ""
DEFUALT_FILE_PATH = "temp_resume.pdf"

current_file_dir = os.path.dirname(os.path.realpath(__file__))

#print(current_file_dir)

args = sys.argv[1:]
if (len(args) == 0):
    file_path = ""

else:
    file_path = args[0]

if file_path == "":
    file_path = DEFUALT_FILE_PATH


try :
    pdf_master = PdfMaster()

    file_path = f'{current_file_dir}/{file_path}'


    binary_data = pdf_master.pdf_to_binary(file_path)



    pdf_master.set_bin_data(binary_data)

    pdf_master.read_pdf_binary(binary_data)


    phone_number = pdf_master.get_phone_nb()
    email = pdf_master.get_email()
    adrs = pdf_master.get_address()

    skilss = pdf_master.get_skillsAdvancedBinary()

    data = {
        'phone_number': phone_number,
        'email': email,
        'adrs': adrs,
        'skills': skilss
    }

    pdf_master.make_json(file_name='output', data=data, path=current_file_dir)

    print("done")

except Exception as e:
    print(e)

