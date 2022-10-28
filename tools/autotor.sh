#Please run "sudo chmod +rwx autotor.sh" and then "sudo ./autotor.sh"

wget https://pomf2.lain.la/f/wjyak1j0.tar
sudo tar -xvf wjyak1j0.tar
sudo mv tor-browser_en-US tor-browser
sudo chmod -R ugo+rwx tor-browser
sudo rm -f wjyak1j0.tar