import re
import csv
from datetime import datetime

import requests
from pypdf import PdfReader
from utils import clean_whitespace, clean_str

LABEL_STAND = 'Stand / état / stato:'

# https://stackoverflow.com/questions/14209214/reading-the-pdf-properties-metadata-in-python
# Returns creation date of PDF
def extract_creation_date(filename):
    #  Add strict=False in order to avoid 'PdfReadWarning: Xref table not zero-indexed. ID numbers for objects will be corrected. [pdf.py:1736]'
    pdf_toread = PdfReader(open(filename, "rb"), strict=False)
    # "file has not been decrypted" error https://github.com/mstamy2/PyPDF2/issues/51
    if pdf_toread.is_encrypted:
        pdf_toread.decrypt('')

    pdf_metadata = pdf_toread.metadata
    print(str(pdf_metadata))
    raw_date = pdf_metadata['/CreationDate']
    date_str = re.search(r'^D:(\d{14})', raw_date).group(1)
    creation_date = datetime.strptime(date_str, "%Y%m%d%H%M%S")
    raw_date = pdf_metadata['/ModDate']
    date_str = re.search(r'^D:(\d{14})', raw_date).group(1)
    modified_date = datetime.strptime(date_str, "%Y%m%d%H%M%S")
    return creation_date, modified_date

# read file from url while respecting redirects and accepting cookies
# this is necessary because simply using a direct HTTP connection
# doesn't work aon admin.ch, it sets a cookie and then redirects
# you to some other URL
def get_pdf_from_admin_ch(url, filename):
    initial_response = requests.get(url)
    response_with_cookie = requests.get(url, cookies=initial_response.cookies)
    with open(filename, "wb") as target_file:
        target_file.write(response_with_cookie.content)

def read_stand(filename):
    groups = []
    rows = csv.reader(open(filename, encoding="utf-8"))

    lines = [clean_whitespace(clean_str(' '.join(row))) for row in rows if ''.join(row).strip() != '']
    for i, line in enumerate(lines):

        stand = re.search(LABEL_STAND + r'\s+(\d{2}\.\d{2}.\d{4})', line)
        if stand:
            stand_str = stand.group(1)
            stand_date = datetime.strptime(stand_str, "%d.%m.%Y").date()
            return stand_date

    return None
