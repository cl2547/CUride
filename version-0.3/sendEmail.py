import smtplib
from flask import Flask, render_template, redirect, url_for,request
from flask import make_response
app = Flask(__name__)

gmail_user = 'curide.customerservice@gmail.com'  
gmail_password = 'xiangshiyi'

@app.route('/sendEmail', methods=['GET', 'POST'])
def login():
   message = None
   if request.method == 'POST':
        destEmail = request.form['email']
        code = request.form['code']
        resp = sendEmail(destEmail,code)
        return resp

if __name__ == "__main__":
    app.run(debug = True)

#try access login_and_signup.html


#send email from cu customer service to destination
def sendEmail(dest, code):
	# email template
	sent_from = 'curide.customerservice@gmail.com'  
	to = dest 
	subject = 'CU Ride: Please enter the verification oode'  
	body = 'Your verification code is: \n '+code+'\n- You'

	email_text = """  
	From: %s  
	To: %s  
	Subject: %s

	%s
	""" % (sent_from, ", ".join(to), subject, body)

	try:  
	    server = smtplib.SMTP_SSL('smtp.gmail.com', 465)
	    server.ehlo()
	    server.login(gmail_user, gmail_password)
	    print("success")
	    server.sendmail(sent_from, to, email_text)
	    server.close()
	    print("closed!")
	    return True
	    # ...send emails
	except:  
	    print ('Something went wrong...')
	    return False


