import os
current_file_dir = os.path.dirname(os.path.realpath(__file__))

from cryptography.hazmat.primitives.asymmetric import rsa, padding
from cryptography.hazmat.primitives import hashes, serialization

# Generate RSA keys
private_key = rsa.generate_private_key(
    public_exponent=65537,
    key_size=2048,
)

public_key = private_key.public_key()

# Serialize the public key to share it
public_key_pem = public_key.public_bytes(
    encoding=serialization.Encoding.PEM,
    format=serialization.PublicFormat.SubjectPublicKeyInfo
)
f = open(f"{current_file_dir}/public_key.pem", "wb")
f.write(public_key_pem)
f.close()

# Sign data
data = b"first data to sign"
signature = private_key.sign(
    data,
    padding.PSS(
        mgf=padding.MGF1(hashes.SHA256()),
        salt_length=padding.PSS.MAX_LENGTH
    ),
    hashes.SHA256()
)

import qrcode
import base64

# Combine data and signature
data_signature_combined = data + b"||" + base64.b64encode(signature)

# Generate QR code
qr = qrcode.QRCode(
    version=1,
    error_correction=qrcode.constants.ERROR_CORRECT_L,
    box_size=10,
    border=4,
)
qr.add_data(data_signature_combined)
qr.make(fit=True)

img = qr.make_image(fill_color="black", back_color="white")
img.save(f"{current_file_dir}/signed_qr.png")

