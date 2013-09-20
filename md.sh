#!/bin/sh
sudo tcpdump -X \(dst host sector181.c1.galactic.wonderhill.com and tcp dst port 80 and tcp[tcpflags] \& tcp-push !=0\) | ./meltdown 

