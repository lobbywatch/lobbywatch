# -*- coding: utf-8 -*-

from PyPDF2 import PdfFileReader
import re
import requests
from datetime import datetime

# https://stackoverflow.com/questions/14209214/reading-the-pdf-properties-metadata-in-python
# Returns creation date of PDF
def extract_creation_date(filename):
    #  Add strict=False in order to avoid 'PdfReadWarning: Xref table not zero-indexed. ID numbers for objects will be corrected. [pdf.py:1736]'
    pdf_toread = PdfFileReader(open(filename, "rb"), strict=False)
    # "file has not been decrypted" error https://github.com/mstamy2/PyPDF2/issues/51
    if pdf_toread.isEncrypted:
        pdf_toread.decrypt('')
    pdf_info = pdf_toread.getDocumentInfo()
    #print(str(pdf_info))
    # PDF Reference, 3.8.3 Dates, http://www.adobe.com/content/dam/Adobe/en/devnet/acrobat/pdfs/pdf_reference_1-7.pdf
    # A date is an ASCII string of the form (D:YYYYMMDDHHmmSSOHH'mm')
    # Examle: D:20170508085336+02'00'
    raw_date = pdf_info['/CreationDate']
    #print(str(raw_date))
    date_str = re.search('^D:(\d{14})', raw_date).group(1)
    #print(str(date_str))
    pdf_date = datetime.strptime(date_str, "%Y%m%d%H%M%S")
    #print(str(date))
    return pdf_date

# read file from url while respecting redirects and accepting cookies
# this is necessary because simply using a direct HTTP connection
# doesn't work aon admin.ch, it sets a cookie and then redirects
# you to some other URL
def get_pdf_from_admin_ch(url, filename):
    initial_response = requests.get(url)
    response_with_cookie = requests.get(url, cookies=initial_response.cookies)
    with open(filename, "wb") as target_file:
        target_file.write(response_with_cookie.content)
