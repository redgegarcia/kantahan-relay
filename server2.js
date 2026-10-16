// Kantahan Live relay v2: multi-room.
// Hindi nagbabago ang message protocol ng page: JSON at audio packets, ipinapasa lang sa kasama sa parehong room.
const http = require('http');
const crypto = require('crypto');
const { WebSocketServer } = require('ws');

const PORT = process.env.PORT || 8080;
const MAX_PER_ROOM = parseInt(process.env.MAX_PER_ROOM || '10', 10); // limit kada room (libreng tier)
const MAX_ROOMS = parseInt(process.env.MAX_ROOMS || '20', 10);       // proteksyon sa libreng instance
const ALLOWED_ORIGINS = (process.env.ALLOWED_ORIGINS || 'https://kalugar.com,https://www.kalugar.com,http://localhost,http://127.0.0.1')
  .split(',').map(s => s.trim()).filter(Boolean);

const rooms = new Map(); // code -> { code, name, pw, hostKey, clients:Set, created }
const CODE_RE = /^[A-Z0-9]{4,8}$/;

function hashPw(pw) { return crypto.createHash('sha256').update('kl-room:' + pw).digest('hex'); }
function safeEq(a, b) {
  const x = Buffer.from(String(a)), y = Buffer.from(String(b));
  return x.length === y.length && crypto.timingSafeEqual(x, y);
}
function cleanText(s, n) { return String(s || '').replace(/[\u0000-\u001f<>]/g, '').trim().slice(0, n); }

function originOk(origin) {
  if (!origin) return true; // non-browser (test) clients
  return ALLOWED_ORIGINS.some(o => origin === o || origin.startsWith(o + ':'));
}

// HTTP: /rooms = listahan ng rooms para sa modal ng page; / = health check
const server = http.createServer((req, res) => {
  const origin = req.headers.origin;
  const headers = { 'Content-Type': 'application/json', 'Cache-Control': 'no-store' };
  if (origin && originOk(origin)) headers['Access-Control-Allow-Origin'] = origin;
  if (req.method === 'OPTIONS') { res.writeHead(204, headers); return res.end(); }
  if (req.url.split('?')[0] === '/rooms') {
    const list = [...rooms.values()].map(r => ({
      code: r.code, name: r.name, count: r.clients.size, cap: MAX_PER_ROOM, locked: !!r.pw
    })).sort((a, b) => b.count - a.count);
    res.writeHead(200, headers);
    return res.end(JSON.stringify({ rooms: list, cap: MAX_PER_ROOM }));
  }
  res.writeHead(200, { 'Content-Type': 'text/plain' });
  res.end('Kantahan relay OK');
});

const wss = new WebSocketServer({ server, maxPayload: 1024 * 1024 });

function reject(ws, reason) {
  try { ws.send(JSON.stringify({ type: 'ROOM_ERROR', reason })); } catch (e) {}
  setTimeout(() => { try { ws.close(4000, reason); } catch (e) {} }, 100);
}

wss.on('connection', (ws, req) => {
  if (!originOk(req.headers.origin)) { try { ws.close(4003, 'origin'); } catch (e) {} return; }

  const q = new URL(req.url, 'http://x').searchParams;
  const code = cleanText(q.get('room'), 8).toUpperCase();
  const mode = q.get('mode') === 'create' ? 'create' : 'join';
  const pw = String(q.get('pw') || '').slice(0, 32);
  const hk = String(q.get('hk') || '').slice(0, 64);

  if (!CODE_RE.test(code)) return reject(ws, 'badcode');

  let room = rooms.get(code);
  let isHost = false;

  if (!room) {
    if (mode !== 'create') return reject(ws, 'notfound');
    if (rooms.size >= MAX_ROOMS) return reject(ws, 'serverfull');
    room = {
      code,
      name: cleanText(q.get('name'), 24) || ('Room ' + code),
      pw: pw ? hashPw(pw) : null,
      hostKey: crypto.randomBytes(16).toString('hex'),
      clients: new Set(),
      created: Date.now()
    };
    rooms.set(code, room);
    isHost = true;
  } else {
    if (mode === 'create' && !hk) return reject(ws, 'exists'); // collision ng code
    if (hk && safeEq(hk, room.hostKey)) isHost = true;         // reconnect ng host
    else if (room.pw && !safeEq(hashPw(pw), room.pw)) return reject(ws, pw ? 'wrongpw' : 'needpw');
  }

  if (room.clients.size >= MAX_PER_ROOM) return reject(ws, 'full');

  room.clients.add(ws);
  ws.room = room;
  ws.isAlive = true;
  ws.on('pong', () => { ws.isAlive = true; });

  // Unang message sa client: info ng room (ito lang ang pinagkakatiwalaan ng page)
  ws.send(JSON.stringify({
    type: 'ROOM_INFO', code: room.code, name: room.name, locked: !!room.pw,
    host: isHost, hostKey: isHost ? room.hostKey : undefined, cap: MAX_PER_ROOM
  }));

  ws.on('message', (data, isBinary) => {
    for (const c of room.clients) {
      if (c !== ws && c.readyState === 1) c.send(data, { binary: isBinary });
    }
  });

  ws.on('close', () => {
    room.clients.delete(ws);
    if (room.clients.size === 0) {
      // maliit na palugit para sa reconnect ng host bago burahin
      setTimeout(() => {
        if (rooms.get(room.code) === room && room.clients.size === 0) rooms.delete(room.code);
      }, 15000);
    }
  });
  ws.on('error', () => {});
});

// Alisin ang patay na koneksyon (at para gising ang socket)
setInterval(() => {
  wss.clients.forEach(ws => {
    if (ws.isAlive === false) return ws.terminate();
    ws.isAlive = false;
    try { ws.ping(); } catch (e) {}
  });
}, 30000);

server.listen(PORT, () => console.log('Kantahan relay v2 on ' + PORT));
