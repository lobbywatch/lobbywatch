# keep old dumps and other archived data from taking up all disk space
find /home/almalinux/scraper/prod_bak/bak /home/almalinux/scraper/prod_bak/archive -mtime +30 -delete
