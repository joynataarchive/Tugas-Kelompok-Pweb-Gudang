import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

from pypdf import PdfReader
reader = PdfReader(r'C:\Users\agung\.gemini\antigravity-ide\brain\a0ed4321-a2ab-4797-a2f2-fe71c4dd4145\media__1784557392156.pdf')
print(f'Total pages: {len(reader.pages)}')
# Read first 2 pages for context
for i, page in enumerate(reader.pages[:2]):
    print(f'\n--- PAGE {i+1} ---')
    text = page.extract_text()
    if text:
        print(text)
