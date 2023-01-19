#Settings
echo Please input the url of the item you wish to download.
read link
#The downloader
for (( ; ; ))
do
    wget $link -O DELETEME
    rm -rf DELETEME
done