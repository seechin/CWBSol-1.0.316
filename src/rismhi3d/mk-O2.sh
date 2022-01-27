#!/bin/sh
shscript=mk-O2.sh
if [ -z $1 ]; then
  echo "$shscript : compile script. Usage:" 
  echo " # this program requires FFTW to work:" 
  echo "  export FFTWPATH_I=fftw_root_path/include"
  echo "  export FFTWPATH_L=fftw_root_path/lib"
  echo "  export LIBFFTW=fftw3"
  echo " # only when you want to read XTC, then enable _GROMACS_ in header.h and:"
  echo "  export GMXPATH_I=gromacs_root_path/include/gromacs"
  echo "  export GMXPATH_L=gromacs_root_path/lib"
  echo "  export LIBGMX=gmx"
  echo " # the optimization of compiling:"
  echo "  export GCCOPT=-O2"
  echo " # start to compile everything"
  echo "  $shscript gensolvent gmxtop2solute ts4sdump rismhi3d"
  exit
fi
# -----------------------------------------------
# please specify the paths of libraries here
if [ -z "$FFTWPATH_I" ]; then   FFTWPATH_I=`pwd`/../../fftw/fftw3/include ; fi
if [ -z "$FFTWPATH_L" ]; then   FFTWPATH_L=`pwd`/../../fftw/fftw3/lib     ; fi
if [ -z "$LIBFFTW"    ]; then   LIBFFTW=fftw3                             ; fi
if [ ! -z $GMXLDLIB ]; then
    GMXPATH_I=$GMXLDLIB/../include;
    GMXPATH_L=$GMXLDLIB;
fi
if [ -z "$LIBGMX"     ]; then   LIBGMX=gmx                                ; fi
if [ ! -z $GMXBIN ]&&[ -e $GMXBIN/gmx ]; then LIBGMX=gromacs; fi
if [ -z "$GCCOPT"     ]; then   GCCOPT=" -O2"                             ; fi
# -----------------------------------------------

# -----------------------------------------------
# find the compilation options from reading header.h
headfile=header.h
if [ ! -e $headfile ]; then
  echo $shscript : error : cannot find $headfile. Please compile in folder that contains the source code files.
  exit
fi
allow_mp=`cat $headfile | awk '{if($1=="#define" && $2=="_LOCALPARALLEL_")printf("allow\n")}'`
allow_gmx=`cat $headfile | awk '{if($1=="#define" && ($2=="_GROMACS_"))printf("allow\n")}'`
    if [ -z "$GMXLDLIB" ]; then allow_gmx=""; fi
allow_interact=`cat $headfile | awk '{if($1=="#define" && $2=="_INTERACTIVE_")printf("allow\n")}'`
# -----------------------------------------------

# -----------------------------------------------
# prepare the compilation options
CPPFLAGS=" -I$FFTWPATH_I"
LDFLAGS=" -lm -lz -L$FFTWPATH_L -l$LIBFFTW"
LDFLAGSGMX=$LDFLAGS;

if [ ! -z "$allow_gmx" ]; then
    CPPFLAGS=$CPPFLAGS" -I$GMXPATH_I";
    LDFLAGS=$LDFLAGS" -L$GMXPATH_L";
    LDFLAGSGMX=$LDFLAGSGMX" -L$GMXPATH_L -l$LIBGMX";
fi
if [ ! -z $allow_interact ]||[ ! -z "$allow_mp" ]; then
  LDFLAGS=$LDFLAGS" -lpthread"
  LDFLAGSGMX=$LDFLAGSGMX" -lpthread"
fi
# -----------------------------------------------


# -----------------------------------------------
# do compilation of everythings

for pn in $@; do
    if [ $pn == "rismhi3d" ]||[ $pn == "gensolvent" ]; then
        echo g++ $GCCOPT $pn.cpp -o $pn $CPPFLAGS $LDFLAGSGMX -ldl
        g++ $GCCOPT $pn.cpp -o $pn $CPPFLAGS $LDFLAGSGMX -ldl
    else
        echo g++ $GCCOPT $pn.cpp -o $pn $CPPFLAGS $LDFLAGS -ldl
        g++ $GCCOPT $pn.cpp -o $pn $CPPFLAGS $LDFLAGS -ldl
    fi
done
# -----------------------------------------------


