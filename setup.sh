#!/bin/bash
# nob1dy 2014
# Script to automate setting up additional tools and pre-requisites for Debian/Kali based systems 
# tested and works with Kali and Debian - yes
# TODO: Tidy up and clean up - Comment code and add arguments
# Add usage etc.

RED="\e[0;31m"
GREEN="\e[0;32m"
YELLOW="\e[0;33m"
BLUE="\e[0;34m"
PURPLE="\e[0;35m"
HPURPLE="\e[1;35m"
IRED="\e[0;91m"
IGREEN="\e[0;92m"
IYELLOW="\e[0;93m"
IBLUE="\e[0;94m"
BRED="\e[1;31m"
BGREEN="\e[1;32m"
BOLD="\e[1m"
RESET="\e[0m"
KALI=$1;



#android;
#nvidia()

#display_usage(){
#if [ $# -ne 1 ]; then
#echo -e "${GREEN}[*]Script to set up KALI or DEBIAN installation and additional scripts"
#echo -e "${GREEN}[*]Usage: $0 [KALI=1] or [KALI=0] "
#exit
#fi
#}

prereqs()
{
  echo -e "${PURPLE}[*]Setting up sudo ${RESET}"
  #apt-get install sudo -y
  visudo
  read line
  apt-get install git -y
  apt-get install gcc -y
  apt-get install build-essentials -y
  apt-get install make -y
  apt-get install linux-headers-$(uname -r|sed 's,[^-]*-[^-]*-,,') -y
  apt-get install gdb -y
  apt-get install mingw32 -y
  apt-get install autoconf -y
  apt-get install flex -y
  apt-get install bison -y
  apt-get install byacc -y
  apt-get install vlan -y
  apt-get install tshark -y
  apt-get install screen -y
  apt-get install ethtool -y # frogger
  apt-get install ccze -y
  dpkg --add-architecture i386
  apt-get install wine -y # for wine when installed on 64bit
  apt-get install wine-bin:i386  
  nmap --script-update
  echo -e "${IBLUE}[*] Perform the following: Application Menu -> Settings -> Appearance (default font size - 10 - changed to 13)${RESET}"
  echo -e "${IBLUE}[*] Terminal Font - default 12 -> 13, set to transparent background , changed font color to white${RESET}"
  echo -e "${IBLUE}[*] Perform the following: Keyboard Shortcuts -> Add custom shortcut -> Terminal - gnome-terminal -> Shortcut : Press CTRL ALT T${RESET}"
  read line

}

###########Generic Utilities and Updates#####################
set_up(){
start=`date`
echo -e "${GREEN}[*]Setting up $(hostname)[laptop]\n$(uname -a) at $start ${RESET}"
sleep 2
echo -e "${RED}[+]Install Updates${RESET}"
apt-get update && apt-get upgrade -y
apt-get install iftop htop mlocate -y

sleep 2
}

kali_set_up()
{

#############KALI#################################################

echo -e "${BLUE}[+]Updating Metasploit${RESET}"
msfupdate
echo -e "${YELLOW}[+] Beef${RESET}"
apt-get install beef-xss -y
apt-get install kate -y
#echo -e "${BLUE}[+] Open Office"
#apt-get install openoffice.org -y
}

nvidia()
{
echo test
#############NVIDIA CHIPSET#######################################
#echo -e "${PURPLE}[+]Adding NVIDIA repository"
#add-apt-repository ppa:ubuntu-x-swat/x-updates
#echo -e "${PURPLE}[+]Setting up Nvidia Drivers "
#apt-get update && apt-get install nvidia-current nvidia-current-modaliases nvidia-settings
#echo -e "${GREEN}[i]Reboot and ${BLUE}nvidia-xconfig"
}



optional(){
#############OPTIONAL##############################################
#echo -e "${PURPLE}[+] Compiz"
#apt-get install compiz -y
#echo -e "${WHITE}[+]Installing tools"
#apt-get install synaptic
apt-get install unetbootin
apt-get install hexedit
echo -e "${GREEN}[*] increase font size${RESET}"
#nano ~/.icewm/preferences 
echo -e "${YELLOW}[+] him"
apt-get install him -y
echo -e "${YELLOW}[+] ranger file explorer${RESET}"
apt-get install ranger -y
##################################################################
}

virtualbox()
{
apt-get install virtualbox virtualbox-guest-x11 virtualbox-guest-utils virtualbox-guest-additions -y
apt-get install virtualbox-ose-dkms -y
}

clients(){
echo -e "${YELLOW}[+] finger${RESET}"
apt-get install finger -y
echo -e "${YELLOW}[+] rlogin,rsh client, putty,tftp, filezilla${RESET}"
apt-get install rsh-client rwho putty -y
apt-get install tftp -y
apt-get install filezilla filezilla-common -y
}

#android(){
######### fix android sdk error (not finding adb)
#dpkg --add-architecture i386
#apt-get install lib32z1 lib32ncurses5
#apt-get install lib32stdc++6

#}
password(){
apt-get install john -y 
#download and have noobify for quick l337 of words
iceweasel https://sites.google.com/site/reusablesec/Home/password-cracking-tools/noobify
#korelogic rules
wget http://contest-2010.korelogic.com/rules.txt -O korelogic-rules-forjohn.txt
}
wireless(){
######### wireless
######  http://askubuntu.com/questions/109260/how-do-i-get-an-intel-ultimate-6300-n-working
#install firmware-iwlwifi
 
	####### As root with your text editor of choice make a new file:
	####### /etc/modprobe.d/customintel6300N.conf
	####### In that file add the line:
	####### options iwlagn bt_coex_active=0
	echo -e "${YELLOW}[+] Adding The following repository to sources.list and setting up wireless${RESET}"
        echo -e "${YELLOW}[+] deb http://http.debian.net/debian/ wheezy main non-free${RESET}"
        echo "deb http://http.debian.net/debian/ wheezy main non-free" > /etc/apt/sources.list
	apt-get install wireless-tools
	apt-get install aircrack-ng
	apt-get install wireless-linux
        apt-get install firmware-iwlwif
       
        #sudo nano /etc/apt/sources.list you need to add 
        #deb http://http.debian.net/debian/ wheezy main non-free
}

sniffers(){
echo -e "${YELLOW}[+] tcpdump , sniffers etc.${RESET}"

#wget http://www.tcpdump.org/release/libpcap-1.6.2.tar.gz
#tar -xvf libpcap-1.6.2 # ./configure --prefix=/usr
#sudo ./configure
#$ sudo make
#$ sudo make install
apt-get install tcpdump -y
apt-get install wireshark -y
apt-get install ngrep -y
git clone https://github.com/superkojiman/snuff/blob/master/snuff.sh #mitm, sslstrip and arp
}

scanning(){
echo -e "${YELLOW}[+] nbtscan${RESET}"
apt-get install nbtscan -y
echo -e "${YELLOW}[+] arp-scan${RESET}"
apt-get install arp-scan -y
echo -e "${YELLOW}[+] ike-scan${RESET}"
apt-get install ike-scan -y
#echo -e "${YELLOW}[+] unicorn-scan"

#echo -e "${YELLOW}[+] nbtscan"
mkdir ./nbtscan
cd nbtscan
wget http://www.unixwiz.net/tools/nbtscan-source-1.0.35.tgz
tar -xvf nbtscan-source-1.0.35.tgz
make
cd ..
echo -e "${YELLOW}[+] yersinia${RESET}"
apt-get install yersinia -y



}

arp_ike_scan()
{

echo -e "${YELLOW}[+] ike-scan"
git clone https://github.com/royhills/ike-scan.git
cd ike-scan
autoreconf --install
./configure --with-openssl
make
make check
make install
cd ..
echo -e "${YELLOW}[+] arp-scan"
git clone https://github.com/royhills/arp-scan.git
cd arp-scan
autoreconf --install
./configure
make check
make install
cd ..
}

labs()
{
echo -e "${PURPLE}[+] PCSL Labs tools download${RESET}"
mkdir ./labs
cd labs
wget --no-check-certificate https://labs.portcullis.co.uk/download/onesixtyone-0.7.tar.gz
wget --no-check-certificate https://labs.portcullis.co.uk/download/udp-proto-scanner-1.1.tar.gz
wget --no-check-certificate https://labs.portcullis.co.uk/download/hoppy-1.8.1.tar.bz2
wget --no-check-certificate https://labs.portcullis.co.uk/download/nopc-0.4.5.tar.bz2
wget --no-check-certificate https://labs.portcullis.co.uk/download/enum4linux-0.8.9.tar.gz
wget --no-check-certificate https://labs.portcullis.co.uk/download/winlanfoe-0.4.tgz
wget --no-check-certificate https://labs.portcullis.co.uk/download/ames.py.tgz
wget --no-check-certificate https://labs.portcullis.co.uk/download/rdp-sec-check-0.9.tgz
wget --no-check-certificate https://labs.portcullis.co.uk/download/iker_v1.1.tar
wget --no-check-certificate https://labs.portcullis.co.uk/download/tools/ssl-cipher-suite-enum-v1.0.0.tar.gz
tar -xvf *.tar.gz
cd ..


}

ssl()
{
git clone https://github.com/drwetter/testssl.sh
git clone https://github.com/google/nogotofail

# for rdp sec

perl -MCPAN -e "install Convert::BER"
}

servers(){
echo -e "${YELLOW}[+] Tftp server${RESET}"
apt-get install atftpd -y
echo "atftpd --daemon --port 69 --bind-address yourip /tmp"
echo "netstat -anu | grep 69"
echo -e "${YELLOW}[+] RDP server"
apt-get install xrdp -y
}

java(){
echo -e "${GREEN}[+] Java, 3rd party${RESET}"
#http://www.oracle.com/technetwork/java/javase/downloads/index.html
#http://www.oracle.com/technetwork/java/javase/downloads/index.html
#Untar the Archive
#tar -xzvf /root/jdk-7u17-linux-x64.tar.gz
#mv jdk1.7.0_17 /opt
#cd /opt/jdk1.7.0_17

#3.This step registers the downloaded version of Java as an alternative, and switches it to be used as the default:

#update-alternatives --install /usr/bin/java java /opt/jdk1.8.0_25/bin/java 1
#update-alternatives --install /usr/bin/javac javac /opt/jdk1.8.0_25/bin/javac 1
#update-alternatives --install /usr/lib/mozilla/plugins/libjavaplugin.so mozilla-javaplugin.so /opt/jdk1.8.0_25/jre/lib/amd64/libnpjp2.so 1
#update-alternatives --set java /opt/jdk1.8.0_25/bin/java
#update-alternatives --set javac /opt/jdk1.8.0_25/bin/javac
#update-alternatives --set mozilla-javaplugin.so /opt/jdk1.8.0_25/jre/lib/amd64/libnpjp2.so


#4. Test

#To check the version of Java you are now running

#java -version

apt-get install default-jre  default-jdk -y
#update-java-alternatives -s java-6-sun
}

discover()
{
mkdir discover
cd discover
git clone https://github.com/leebaird/discover
}


common_exploits()
{
#http://www.commonexploits.com/penetration-testing-scripts/
echo -e "${GREEN}[+] Downloading Common Exploits${RESET}"

mkdir ./common_exploits
cd common_exploits
git clone https://github.com/commonexploits/dtpscan.git
git clone https://github.com/commonexploits/livehosts
git clone https://github.com/commonexploits/port-scan-automation
git clone https://github.com/commonexploits/whatsfree
git clone https://github.com/commonexploits/weape
git clone https://github.com/commonexploits/winocphc
git clone https://github.com/commonexploits/ipgen
git clone https://github.com/commonexploits/vlan-hopping
git clone https://github.com/commonexploits/icmpsh

cd ..
}
smb(){
mkdir smb_tools
cd smb_tools
echo -e "${BLUE}[+] SMBEXEC and RESPONDER"
git clone https://github.com/brav0hax/smbexec.git
git clone https://github.com/SpiderLabs/Responder.git
git clone https://github.com/mubix/FakeNetBIOS
cd ..
}

post_exploit()
{

git clone https://github.com/mubix/post-exploitation.git
git clone https://github.com/mubix/post-exploitation-wiki
git clone https://github.com/mubix/ditto
git clone https://github.com/bidord/pykek
git clone https://github.com/rsmudge/metasploit-loader #rsmudge metasploit loader
git clone https://github.com/ChrisTruncer/EyeWitness.git #eyewitness to scan web servers try default creds and take screenshots

}

shellshock()
{

git clone https://github.com/mubix/shellshocker-pocs

}

heartbleed()
{
git clone https://github.com/sensepost/heartbleed-poc.git
}

clamav()
{
  apt-get install clamav
  apt-get install clamav-freshclam
  udo apt-get install clamtk
  
}


powershell()
{
mkdir powershell
cd powershell
git clone https://github.com/HarmJ0y/PowerUp
git clone https://github.com/samratashok/nishang
git clone https://github.com/clymb3r/PowerSploit
git clone https://github.com/Veil-Framework/Veil.git
git clone https://github.com/trustedsec/unicorn.git
cd ..
}

virtual_machine_kvm()
{

apt-get install virt-manager -y
apt-get install libvirt-bin -y
apt-get install ssh-askpass -y
apt-get install virt-goodies -y
}

#echo -e "${BLUE}[+] NoteCase"
#apt-get install notecase -y
#echo "Make downloads directory"



#wget http://wkhtmltopdf.googlecode.com/files/wkhtmltoimage-0.11.0_rc1-static-i386.tar.bz2
#tar -jxvf wkhtmltoimage-0.11.0_rc1-static-i386.tar.bz2
#cp wkhtmltoimage-i386 /usr/local/bin/
# wget https://github.com/SpiderLabs/Nmap-Tools/blob/master/NSE/http-screenshot.nse -O /usr/share/nmap/scripts/http-screenshot.nse
#mkdir loki
#cd loki
#wget http://www.ernw.de/download/loki/loki-0.2.7.tar.gz
#wget http://www.ernw.de/download/loki/ubuntu-11.04/loki_0.2.7-1_amd64.deb
#wget https://www.c0decafe.de/loki/loki-0.2.4.tar.gz
#wget https://www.c0decafe.de/loki/loki-0.2.4-bt4.diff
#wget http://www.packetstan.com/2011/02/running-loki-on-backtrack-4-r2.html
#cd ..
#wget https://raw.github.com/darkoperator/Metasploit-Plugins/master/pentest.rb -o /opt/metasploit/msf3/plugins/
#wget https://github.com/darkoperator/MDNSRecon/blob/master/mdnsrecon.rb -o /pentest/enumeration/dns/
#wget http://fpdownload.macromedia.com/get/flashplayer/pdc/11.1.102.63/install_flash_player_11_linux.i386.tar.gz
#tar xvzf install_flash_player_11_linux.i386.tar.gz
#mkdir ~/.mozilla/plugins
#cd /opt


#display_usage;
#if [ $KALI == 1 ]; then
#kali_set_up;
#fi




prereqs;
set_up;
clients;
servers;
ssl;
sniffers;
scanning;
smb;
#arp_ike_scan;
#virtualbox;
optional;
password;
common_exploits;
java;
labs;
post_exploit;
powershell;
shellshock;
heartbleed;
#nvidia;
clamav;
#virtual_machine_kvm;
