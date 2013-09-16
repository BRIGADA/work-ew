#include <stdio.h>
#include <iostream>
#include <unistd.h>
#include <vector>
#include <algorithm>
#include <curl/curl.h>

std::vector<std::string> explode(const std::string &str, const std::string &delim, unsigned int limit = 0);

typedef std::vector<std::string> stringlist;

stringlist meltdowns;

size_t write_data(void *ptr, size_t size, size_t nmemb, FILE *stream) {
    size_t written;
    written = fwrite(ptr, size, nmemb, stream);
    return written;
}

void check(std::string packet) {
    std::size_t p = packet.find("meltdown=");
    if(p != std::string::npos && packet.size() >= p + 9 + 40) {
	std::string meltdown = packet.substr(p+9, 40);
	
	stringlist::iterator it = std::find(meltdowns.begin(), meltdowns.end(), meltdown);
	if(it == meltdowns.end()) {
	    meltdowns.push_back(meltdown);
	    std::cout<<"New meltdown: "<< meltdown <<std::endl;
	    // CURL ...
	    CURL *curl;
	    CURLcode res;
	    curl = curl_easy_init();
	    if(curl) {
		curl_easy_setopt(curl, CURLOPT_URL, ("http://edgeworld.local/frontend_dev.php/common/meltdown?value="+meltdown).data());
	        /* example.com is redirected, so we tell libcurl to follow redirection */ 
	        curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1L);
		FILE * outfile = fopen("outfile.dat", "a");
		curl_easy_setopt(curl, CURLOPT_WRITEDATA, outfile);
 
	        /* Perform the request, res will get the return code */ 
	        res = curl_easy_perform(curl);
	        /* Check for errors */ 
	        if(res != CURLE_OK)
	          fprintf(stderr, "curl_easy_perform() failed: %s\n", curl_easy_strerror(res));
	
	        /* always cleanup */ 
	        curl_easy_cleanup(curl);
		fclose(outfile);
	    }
	}
	
    }    
}

int main()
{
    char buf[8192];
    while(true)
    {
	int a = read(0, buf, sizeof(buf));
	if(a == -1) {
	    return 0;
	}

	stringlist SL = explode(std::string(buf, a), "\n");
	std::string packet;
	for(stringlist::iterator it = SL.begin(); it != SL.end(); ++it)
	{
	    if((it->size() > 51) && ((*it)[0] == '\t')) {
		packet += it->substr(51);
	    }
	    else {
		check(packet);
		packet.clear();
	    }
	}
	check(packet);
    }
}

std::vector<std::string> explode(const std::string &str, const std::string &delim, unsigned int limit)
{
    std::vector<std::string> result;

    std::string::size_type start = 0;
    std::string::size_type pos = 1;

    while(--limit && (start + 1 < str.size() + delim.size()) && (delim.empty() || (pos = str.find(delim, start)) != std::string::npos)) {
	result.push_back(str.substr(start, pos - start));
	start = pos + delim.size();
	++pos;
    }

    result.push_back(str.substr(start));

    return result;
}