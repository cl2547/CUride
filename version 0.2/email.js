var clientId = '424885657192-hbj0gnaf8cidud73fnmf016e7ncvtmpd.apps.googleusercontent.com';
var apiKey = 'AIzaSyCDA3DKHBDA_8aKGnTiepDqWmdmAhtm7SM';
var scopes =
  'https://www.googleapis.com/auth/gmail.readonly '+
  'https://www.googleapis.com/auth/gmail.send';

function loadGmailApi() {
  gapi.client.load('gmail', 'v1', displayInbox);
}


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
    $('#authorize-button').remove();
    $('.table-inbox').removeClass("hidden");
    $('#compose-button').removeClass("hidden");
  } else {
    $('#authorize-button').removeClass("hidden");
    $('#authorize-button').on('click', function(){
      handleAuthClick();
    });
  }
}

function loadGmailApi() {
  gapi.client.load('gmail', 'v1', displayInbox);
}

function sendEmail()
{
  sendMessage(
    {
      'To': $('#email').val(),
      'Subject': 'Please verify your email'
    },
    'Your access code is: ' + $('#verificationCode')
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