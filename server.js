const { WebSocketServer } = require('ws');

const PORT = process.env.PORT || 8080;
const wss = new WebSocketServer({ port: PORT });

console.log(`[Kantahan Relay] Server running on port ${PORT}`);

wss.on('connection', (ws) => {
  console.log('[Kantahan Relay] Bagong user ang kumonekta!');

  ws.on('message', (data, isBinary) => {
    // I-broadcast ang audio data sa lahat ng ibang nakikinig
    wss.clients.forEach((client) => {
      if (client !== ws && client.readyState === 1) {
        client.send(data, { binary: isBinary });
      }
    });
  });

  ws.on('close', () => {
    console.log('[Kantahan Relay] May umalis sa room.');
  });
});