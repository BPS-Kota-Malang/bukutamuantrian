const wppconnect = require('@wppconnect-team/wppconnect');

wppconnect
  .create()
  .then((client) => start(client))
  .catch((error) => console.log(error));


  function start(client)
  {
    console.log("client has started")
  }


  function listen_messages(client)
  {
    client.onMessage((msg) => {
        const wa_id = msg.from
        const message = "This is a reply from WPP Connect"
        client.sendMessage(wa_id, message)
    })


  }

