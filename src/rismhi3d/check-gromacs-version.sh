#!/bin/bash

if [ -z "$GMXLDLIB" ]||[ ! -e $GMXLDLIB ]; then
    printf "%d" 0; exit;
fi

if [ ! -e $GMXLDLIB/../bin/gmx ]; then
    printf "%d" 4; exit
fi

echo `$GMXLDLIB/../bin/gmx -version | awk '{if($1=="GROMACS"&&$2=="version:")print $NF}' | awk 'BEGIN{FS="."}{printf("%d",$1)}' `

