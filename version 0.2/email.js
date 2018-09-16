function loadGmailApi() {
  gapi.client.load('gmail', 'v1');
}

// function displayInbox() {
//         var request = gapi.client.gmail.users.messages.list({
//           'userId': 'me',
//           'labelIds': 'INBOX',
//           'maxResults': 10
//         });
//         request.execute(function(response) {
//           $.each(response.messages, function() {
//             var messageRequest = gapi.client.gmail.users.messages.get({
//               'userId': 'me',
//               'id': this.id
//             });
//             messageRequest.execute(appendMessageRow);
//           });
//         });
//       }

var clientId = '424885657192-0l5e8hqq3s6ekeac8sd8e4jgt510sg2r.apps.googleusercontent.com';
var apiKey = 'AIzaSyAd42WZQXn-iwmDXW38zjY0XhO3-fH5k7Q';

var scopes =
  'https://www.googleapis.com/auth/gmail.readonly '+
  'https://www.googleapis.com/auth/gmail.send';

function handleClientLoad() {
  gapi.client.setApiKey(apiKey);
  window.setTimeout(checkAuth, 1);
}

function checkAuth() {
  gapi.auth.authorize({
    client_id: clientId,
    scope: scopes,
    immediate: true
  }, handleAuthResult);
}

function handleAuthClick() {
  gapi.auth.authorize({
    client_id: clientId,
    scope: scopes,
    immediate: false
  }, handleAuthResult);
  return false;
}

function handleAuthResult(authResult) {
  if(authResult && !authResult.error) {
    loadGmailApi();
  } else {
      handleAuthClick();
    };
  }


function getVerificationCode(){
  code = $('#code').text();
  return code;
}
function sendEmail()
{
  sendMessage(
    {
      'To': $('#email').val(),
      'Subject': 'Please verify your email'
    },
    'Your access code is: ' + getVerificationCode()
  );

  return false;
}

function sendMessage(headers_obj, message, callback)
{
  var email = '';

  for(var header in headers_obj)
    email += header += ": "+headers_obj[header]+"\r\n";

  email += "\r\n" + message;

  var sendRequest = gapi.client.gmail.users.messages.send({
    'userId': 'me',
    'resource': {
      'raw': window.btoa(email).replace(/\+/g, '-').replace(/\//g, '_')
    }
  });

  return sendRequest.execute(callback);
}