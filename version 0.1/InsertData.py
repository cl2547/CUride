# Spider input algorithm to insert random human data.
# 
# For testing purpose only
# 
# Author: Xiang Shiyi
# Time: 2018/09/07
# 
# For CU-Ride project only.
# --------------------------------------------------------------------

import requests

import numpy as np 
import random
import string
import datetime
from random import randrange
from datetime import timedelta

keys = np.array(['Name','Phone','Wechat','Email','Date','Time',
	'FromCity','ToCity','NoOfPpl','Price','Type','TextArea'])

def simulate_a_person():
	pyRefIndexName = int(np.random.rand(1)*10000)
	pyRefIndexName = str(pyRefIndexName)
	print(pyRefIndexName)

	pyRefIndexPhone = int(np.random.rand(1)*10000000000)
	pyRefIndexPhone = str(pyRefIndexPhone)

	
	pyRefIndexWechat = ''.join(random.choices(string.ascii_uppercase + string.digits, k=   10   ))

	ini = ''.join(random.choices(string.ascii_uppercase + string.digits, k=   6   ))
	# num = str(int(np.random.rand(1)*10000))
	pyRefIndexEmail = ini + '@cornell.com'

	pyRefIndexDate = '2018-09-07'

	pyRefIndexTime = '1:30 PM'

	indd = int(int(np.random.rand(1)*10000) % 3)
	choi = np.array(['Ithaca', 'New York', 'Washington DC'])
	pyRefIndexFromCity = choi[indd]

	indd = int(int(np.random.rand(1)*10000) % 3)
	choi = np.array(['Ithaca', 'New York', 'Washington DC'])
	pyRefIndexToCity = choi[indd]

	pyRefIndexNoOfPpl = int(np.random.rand(1)*10)
	pyRefIndexNoOfPpl = str(pyRefIndexNoOfPpl)

	pyRefIndexPrice = int(np.random.rand(1)*100)
	pyRefIndexPrice = str(pyRefIndexPrice)

	pyRefIndexType = 'ask' if int(np.random.rand(1)*10) > 5 else 'offer'

	pyRefIndexTextArea = ''.join(random.choices(string.ascii_uppercase + string.digits, k=   50   ))

	return np.array([
		pyRefIndexName,
		pyRefIndexPhone,
		pyRefIndexWechat,
		pyRefIndexEmail,
		pyRefIndexDate,
		pyRefIndexTime,
		pyRefIndexFromCity,
		pyRefIndexToCity,
		pyRefIndexNoOfPpl,
		pyRefIndexPrice,
		pyRefIndexType,
		pyRefIndexTextArea
		])


curide_url = "http://curide.000webhostapp.com/CURIDE-src/index.php"
def get_post_content():
	values = simulate_a_person()

	curide_data = {}
	for k, v in zip(keys, values):
		curide_data[k] = v
	curide_data['database_action'] = 'insert'
	return curide_url, curide_data

# print(curide_data)
# exit(0)
# r = requests.post("http://bugs.python.org", data={'number': 12524, 'type': 'issue', 'action': 'show'})


for i in range(10):
	curide_url, curide_data = get_post_content()
	r = requests.post(curide_url, data=curide_data)
	print(r.status_code, r.reason)
	print(r.text[:300] + '...')
	if str(r.status_code) != str(200):
		break
