from PdfAnalyserHamaBTW.PdfMaster import PdfMaster
import base64
import sys


pdf_master = PdfMaster()
# Example usage
folder_name = 'data'
file_path = f'{folder_name}/exp3.pdf'  # Replace with the path to your PDF file
#pdf_master.set_file_path(file_path)
#pdf_master.read_pdf(file_path)

#binary_data = pdf_master.pdf_to_binary(file_path)

read_data = pdf_master.read_json('input.json')
binary_data = pdf_master.convert_base64_to_string_then_to_binary(read_data['pdf_data'])
print(binary_data)

pdf_master.set_bin_data(binary_data)

pdf_master.read_pdf_binary(binary_data)


phone_number = pdf_master.get_phone_nb()
email = pdf_master.get_email()
adrs = pdf_master.get_address()

skilss = pdf_master.get_skillsAdvancedBinary()

# print(pdf_master.get_pdf_data())
print("====================================")
print(phone_number)
print(email)
print(adrs)
print(skilss)

data = {
    'phone_number': phone_number,
    'email': email,
    'adrs': adrs,
   'skills': skilss
}


# Convert bytes to a string (if necessary)
pdf_base64_str = pdf_master.convert_to_base64_then_to_text(binary_data)

data1 = {
    'pdf_data': pdf_base64_str,
}

pdf_master.make_json(file_name='output', data=data)

#pdf_master.make_json(file_name='input', data=data1)



