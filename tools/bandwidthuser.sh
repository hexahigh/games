#Settings
echo Please input the url of the item you wish to download.
read link
echo Do you want to display how many times you have downloaded the file?
echo True/False
read shownum
let num = 0
#The downloader
for (( ; ; ))
do
    wget $link -O DELETEME
    rm -rf DELETEME
    let num ++ 1
    while [ "$shownum" = "true" ]
        echo $num
done