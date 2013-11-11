#!/bin/sh

tshark 'tcp port 80 and host c1.galactic.wonderhill.com' -l -R 'http.request.method == "GET"' -T fields -e http.request.uri | ./symfony game:meltdown

