#!/bin/bash
if [ ! -e code-rismhi3d ]; then
    echo Extract the core package
    tar -zxf code-rismhi3d.tar.gz
fi

echo " " >  ver.php

echo Compile the core package
cd code-rismhi3d
bash ../mk-O2.sh ts4sdump gmxtop2solute gensolvent heatmap rismhi3d
if [ -e ts4sdump ]; then mv ts4sdump ..; fi
if [ -e gmxtop2solute ]; then mv gmxtop2solute ..; fi
if [ -e gensolvent ]; then mv gensolvent ..; fi
if [ -e heatmap ]; then mv heatmap ..; fi
if [ -e rismhi3d ]; then mv rismhi3d ..; fi

cd ..
if [ -e ts4sdump ]&&[ -e gmxtop2solute ]&&[ -e rismhi3d ]; then
    echo success!
    cp code-rismhi3d/header.h code-rismhi3d/header.txt
    ./rismhi3d
    ./ts4sdump
    ./gmxtop2solute
    if [ -e gensolvent ]; then ./gensolvent; fi
    if [ -e heatmap ]; then ./heatmap; fi
    ver_rismhi3d=(`./rismhi3d|while read bin ver; do echo $ver;done`); printf "<?php\n  \$software_version = \"%s\";\n?>\n" $ver_rismhi3d > ver.php 
else
    echo failed!
fi
