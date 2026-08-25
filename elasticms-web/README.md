# ElasticMS Web

ElasticMS Web is a stateless application which can generate an informative website from content indexed in an elasticsearch cluster.

Resources
---------

* [Documentation](https://ems-project.github.io/#/elasticms-web/index)
* [Report issues](https://github.com/ems-project/elasticms/issues) and
  [send Pull Requests](https://github.com/ems-project/elasticms/pulls)
  in the [elasticMS mono repository](https://github.com/ems-project/elasticms)


Time out après 10s: 
```shell
docker compose exec toxiproxy \
    /toxiproxy-cli \
    -h localhost:8474 \
    toxic \
    add -t timeout -a timeout=10000 \
    s3
```

vérifier les toxics actifs :

```shell
docker compose exec toxiproxy \
    /toxiproxy-cli -h localhost:8474 inspect s3
```

Remove toxic `timeout_downstream`
```shell
docker compose exec toxiproxy \
    /toxiproxy-cli \
    -h localhost:8474 \
    toxic remove \
    -n timeout_downstream \
    s3
```


Latence de 200ms +/- 100ms:
```shell
docker compose exec toxiproxy \
    /toxiproxy-cli \
    -h localhost:8474 \
    toxic add \
    -t latency \
    -a latency=200 \
    -a jitter=100 \
    s3
```


Edit latence set 500ms +/- 200ms:
```shell
docker compose exec toxiproxy \
    /toxiproxy-cli \
    -h localhost:8474 \
    toxic update \
    -n latency_downstream \
    -a latency=500 \
    -a jitter=200 \
    s3
```

Remove toxic `latency_downstream`
```shell
docker compose exec toxiproxy \
    /toxiproxy-cli \
    -h localhost:8474 \
    toxic remove \
    -n latency_downstream \
    s3
```