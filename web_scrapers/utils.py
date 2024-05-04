import re
import unicodedata as ud

def clean_whitespace(str):
    if str == None: return None
    return re.sub(r'\s+', ' ', str).strip()

def escape_SQL(str):
    return _escape_string(str)

# quote string variable with ' or if None return NULL
def _quote_str_or_NULL(str):
    return 'NULL' if str is None else "'" + str + "'"


# simple esape function for input strings
def _escape_string(string):
    if string is None: return None
    result = string.replace('\\', '\\\\').replace("'", "''").replace('\n', '\\n')
    return result

def escape_newlines(str):
    if str is None: return None
    return '\\n'.join(str.splitlines())

# the current date formatted as a string MySQL can understand
def _date_as_sql_string(date):
    return date.strftime("%d.%m.%Y")

def _datetime_as_sql_string(datetime):
    return datetime.strftime("%d.%m.%Y %H:%M:%S")

def clean_str(str):
    if str == None: return None
    return re.sub('[\u2028\u2029\u200B\u2063]', '', re.sub(r'[           ]', ' ', re.sub(r'<[^<]+?>', '', re.sub(r'[«»“”„‟]', '"', re.sub(r"[‐‑‒–—]", "-", re.sub(r"[`‘’‚‹›‛]", "'", ud.normalize('NFC', str))))))).replace("\r\n", "\n").replace("\r", "\n").strip()

def replace_bullets(str):
    if str == None: return None
    return re.sub(r'^(-\s|•\s|)', '* ', str.strip()) if str.count('- ') <= 1 or str.count('- und ') > 0 else str.strip()
