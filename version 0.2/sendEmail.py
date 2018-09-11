from apiclient.discovery import build
from httplib2 import Http
from oath2client import file, client, tools

SCOPES = "https://curide.000webhostapp.com"
CLIENT_SECRET = "client_secret.json"

store = file.Storage('storage.json')
credz = store.get()
if not credz or credz.invalid:
  flow = client.flow_from_clientsecrets(CLIENT_SECRET, SCOPES)
  credz = tools.run(flow,store)
SERVICE = build('gmail', "v1", http=credz.authorize(Http()))

threads = GMAIL.users.threads().list(userId='').execute.get('')



def create_message(sender, to, subject, message_text):
  """Create a message for an email.

  Args:
    sender: Email address of the sender.
    to: Email address of the receiver.
    subject: The subject of the email message.
    message_text: The text of the email message.

  Returns:
    An object containing a base64url encoded email object.
  """
  message = MIMEText(message_text)
  message['to'] = to
  message['from'] = sender
  message['subject'] = subject
  return {'raw': base64.urlsafe_b64encode(message.as_string())}


  def send_message(service, user_id, message):
  """Send an email message.

  Args:
    service: Authorized Gmail API service instance.
    user_id: User's email address. The special value "me"
    can be used to indicate the authenticated user.
    message: Message to be sent.

  Returns:
    Sent Message.
  """
  try:
    message = (service.users().messages().send(userId=user_id, body=message)
               .execute())
    print 'Message Id: %s' % message['id']
    return message
  except errors.HttpError, error:
    print 'An error occurred: %s' % error