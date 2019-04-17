<table>
        <tr>
            <td><img width="120" src="https://cdnjs.cloudflare.com/ajax/libs/octicons/8.5.0/svg/rocket.svg" alt="onboarding" /></td>
            <td><strong>Archived Repository</strong><br />
            The code of this repository was written during a <a href="https://marmelab.com/blog/2018/09/05/agile-integration.html">Marmelab agile integration</a>. It illustrates the efforts of a new hiree, who had to implement a board game in several languages and platforms as part of his initial learning. Some of these efforts end up in failure, but failure is part of our learning process, so the code remains publicly visible.<br />
        <strong>This code is not intended to be used in production, and is not maintained.</strong>
        </td>
        </tr>
</table>

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
