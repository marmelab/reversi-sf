# reversi-sf

Experimental reversi bot through Facebook messaging API.

## Setup ENV

Create an .env file at the root of the project and edit it from .env-dist pattern

## Run Server

```
make run
```

Your web server is up and running at "localhost"

## Run Tunnel && bot

Install ngrok and run this command

```
make expose
```

Configure your Facebook app with the webhook url (eg: https://blabla.ngrok.com/webhook)
Start to tchat with your bot on the "Page" associated with your app.
