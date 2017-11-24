## Code is used to scan the total amount on a bill


# import the necessary packages

import cv2
import numpy as np
import pytesseract
pytesseract.pytesseract.tesseract_cmd = 'Tesseract-OCR/tesseract'
from PIL import Image
import os
import sys
import re
from werkzeug.utils import secure_filename

from flask import Flask, render_template, url_for,request,json,jsonify

# load the example image
app = Flask(__name__)

@app.route('/') # define the routing for the app functions (similar to a .htaccess file for routing calls to the correct file)
def index():
	return render_template('home.html')

@app.route('/ocr', methods=['GET','POST']) # route /ocr coming as either a GET or a POST request
def captcha():
	files = request.files['file']; # The image is uploaded with the key 'file'
	path=files.filename
	print(files.filename)
	files.save(secure_filename(files.filename)) # save the image of the bill on the server
	
	im = Image.open(path) # image object
	try:
		txt = pytesseract.image_to_string(im)#,config='-psm 10') # convert image to string using the tesseract module
		#print(txt)
		if re.search("(t|T)?.*(o|O).*(t|T)?.*(a|A)?.*(l|L)?.*[0-9]+",txt): # regex to look up for the keyword Total on the bill
			
			amount=re.search("[0-9*],?[0-9]*.?[0-9]*",re.search("(t|T)?.*(o|O).*(t|T)?.*(a|A).*(l|L)?.*[0-9]+",txt).group(0)).group(0) # search for a number following the keyword Total
			print("amount",re.search("[0-9*],?[0-9]*.?[0-9]*",re.search("(t|T)?.*(o|O).*(t|T)?.*(a|A).*(l|L)?.*[0-9]+",txt).group(0)).group(0))
		return "success..image is clear\namount is:rupees  "+str(amount) # display amount if image is clear
	except: # if the image cannot be understood
		return "not a clear or readable image...upload another one..." 

if __name__ == '__main__':
	app.run(debug=True,threaded=True)			
