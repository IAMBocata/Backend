#!/bin/bash

rm -R /run/user/1000/gvfs/smb-share:server=192.168.56.7,share=webserver/BackEndIamBocataProject
mkdir /run/user/1000/gvfs/smb-share:server=192.168.56.7,share=webserver/BackEndIamBocataProject
cp -R /home/yous/IAM_Bocata/Web_IAM_Bokata/BackEndIamBocataProject/* /run/user/1000/gvfs/smb-share:server=192.168.56.7,share=webserver/BackEndIamBocataProject