## Code is used to scan the total amount on a bill


# import the necessary packages

import cv2
import numpy as np
import pytesseract
pytesseract.pytesseract.tesseract_cmd = 'C:/Program Files (x86)/Tesseract-OCR/tesseract'
from PIL import Image
import os
import sys
import re

def captcha():
	'''files = request.files['file']; # The image is uploaded with the key 'file'
	path=files.filename
	print(files.filename)
	files.save(secure_filename(files.filename)) # save the image of the bill on the server
	'''
	'''files=open("name.txt","r")
	name=files.read()
	print(type(name))'''
	im = Image.open("bill.png") # image object
	txt = pytesseract.image_to_string(im)#,config='-psm 10') # convert image to string using the tesseract module
	#print(txt)
	try:
		txt = pytesseract.image_to_string(im)#,config='-psm 10') # convert image to string using the tesseract module
		#print(txt)
		if re.search("(t|T)?.*(o|O).*(t|T)?.*(a|A)?.*(l|L)?.*[0-9]+",txt): # regex to look up for the keyword Total on the bill
			
			amount=re.search("[0-9*],?[0-9]*.?[0-9]*",re.search("(t|T)?.*(o|O).*(t|T)?.*(a|A).*(l|L)?.*[0-9]+",txt).group(0)).group(0) # search for a number following the keyword Total
			print("amount",re.search("[0-9*],?[0-9]*.?[0-9]*",re.search("(t|T)?.*(o|O).*(t|T)?.*(a|A).*(l|L)?.*[0-9]+",txt).group(0)).group(0))
		return "success..image is clear\namount is:rupees  "+str(amount) # display amount if image is clear
	except: # if the image cannot be understood
		return "not a clear or readable image...upload another one..." 
print(captcha())