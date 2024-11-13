const { SerialPort } = require('serialport');
const WebSocket = require('ws');
const http = require('http');
const fs = require('fs');
const path = require('path');

// Configuração da porta serial
const port = new SerialPort({
  path: 'COM4', // Ajuste para a porta correta
  baudRate: 9600
});

// Cria o servidor HTTP para servir o arquivo HTML
const server = http.createServer((req, res) => {
  const filePath = path.join(__dirname, req.url);

  // Verifica se o arquivo solicitado é o index.html
  if (req.method === 'GET' && req.url === '/') {
    fs.readFile(path.join(__dirname, 'index.html'), (err, data) => {
      if (err) {
        res.writeHead(500);
        return res.end('Erro ao carregar a página');
      }
      res.writeHead(200, { 'Content-Type': 'text/html' });
      res.end(data);
    });
  } else if (req.method === 'GET' && req.url === '/style.css') {
    // Serve o arquivo CSS
    fs.readFile(path.join(__dirname, 'style.css'), (err, data) => {
      if (err) {
        res.writeHead(500);
        return res.end('Erro ao carregar o CSS');
      }
      res.writeHead(200, { 'Content-Type': 'text/css' });
      res.end(data);
    });
  } else {
    res.writeHead(404);
    res.end('Página não encontrada');
  }
});

// Configura o servidor WebSocket
const wss = new WebSocket.Server({ server });

wss.on('connection', (ws) => {
  console.log('Cliente conectado ao WebSocket');

  // Envia dados recebidos do Arduino para o cliente via WebSocket
  port.on('data', (data) => {
    console.log('Dados recebidos do Arduino:', data.toString());
    ws.send(data.toString());
  });
});

// Inicia o servidor HTTP e WebSocket na porta 8080
server.listen(8080, () => {
  console.log('Servidor rodando em http://localhost:8080');
});
