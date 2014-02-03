#!/bin/sh

tshark 'host kabam1-a.akamaihd.net' -l -R 'http.request.method == "GET"' -T fields -e http.request.uri

