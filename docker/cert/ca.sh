if [ "$(curl -skL -w '%{http_code}' https://curl.se/ca/cacert.pem -o /data/certs/cacert.pem)" = "200" ]; then
  exit 0
else
  printf 'ERROR: HTTP STATUS CODE NOT 200'
  exit 1
fi
