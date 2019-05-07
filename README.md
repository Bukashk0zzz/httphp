Native PHP HTTP Server - EXPERIMENTAL DRAFT!!!
---
Based on not yet released php async functionality

Features:
1. HTTP/1.x Server
2. Stream request reader
2. Single process & cluster modes
3. Graceful shutdown

To do:
- [X] Rewrite server class
- [X] GET, HEAD, OPTIONS, CONNECT
- [X] POST, PATCH, PUT (Stream body)
- [ ] Echo server (for testing purposes)
- [ ] Intercept memory error
- [ ] Read, Handle and Write timeouts
- [ ] Forms: multipart/form-data, application/x-www-form-urlencoded
- [ ] SSL
- [ ] SIGINT, SIGTERM, SIGKILL
- [ ] Memory metrics

- [ ] Encodings, mb_* functions

- [ ] 408
- [ ] 411
- [X] 413 - Request size limit (headers, body)
- [X] 426 - Upgrade required
- [X] 505 - Version not supported

- [ ] Keep-alive support, HTTP/2
- [ ] file watcher


