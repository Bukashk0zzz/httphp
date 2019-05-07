Native PHP HTTP Server - EXPERIMENTAL DRAFT!!!
---
Based on not yet released php async functionality. 
Package will be split to separate repositories later.

Features:
1. HTTP/1.x Server
2. Stream request reader
3. Single process mode
4. Cluster mode (build on top of IPC and fork)
5. Graceful shutdown

To do:
- [X] Rewrite server class
- [X] GET, HEAD, OPTIONS, CONNECT
- [X] POST, PATCH, PUT (Stream body)
- [X] Echo server (for testing purposes)
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

Run testing echo server:
```
composer install
docker-compose up echo
curl http://127.0.0.1:3000/
```
