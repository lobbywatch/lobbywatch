import unittest
from utils import clean_str

# PYTHONPATH=web_scrapers; python3 -m unittest -v utils_test.py

class TestUtils(unittest.TestCase):

    def test_clean_str_eol(self):
        self.assertEqual('A\nB', clean_str('A\r\nB'))
        self.assertEqual('A\nB', clean_str('A\rB'))
        self.assertEqual('A\nB', clean_str('A\nB'))

    def test_clean_str_dash(self):
        self.assertEqual('-', clean_str('‒'))


if __name__ == '__main__':
    unittest.main()
