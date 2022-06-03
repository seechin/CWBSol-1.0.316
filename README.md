![logo_256.png](https://github.com/seechin/CWBSol/blob/main/images/logo_256.png?raw=true)

CWBSol 1.0.315

# Licence

CWBSol is the graphic interface of 3DRISM-HI (https://github.com/seechin/3DRISMHI).

3DRISM-HI is free software for non-commercial use. You can use, modify or redistribute for non-commercial purposes under terms of the GNU General Public (GPL) License version 3 as published by the Free Software Foundation. A GNU GPL3 Licence is attached to this program.

3DRISM-HI uses FFTW 3 for fast Fourier transformations.

# Run CWBSol

The CWBSol use a server-client architecture for the GUI. The server side requires PHP (>=5), and the client side requires a web browser (Chrome, FireFox, Safari, Edge, etc. Internet Explorer not supported)

## Start CWBSol server with Apache 2

Extract the whole CWBSol package to a sub-folder of your website.

## Start CWBSol server with the build-in server of PHP

php -S YOUR_IP_ADDRESS:A_PORT -t CWBSOL_ROOT_FOLDER

e.g.: php -S 127.0.0.1:8080 -t $HOME/Downloads/CWBSol-master

# Install the tools of CWBSol

3DRISM-HI requires FFTW (double precision) to do FFT. Other optional libraries include ZLIB and GROMACS, which are used for data compression (ZLIB) and XTC processing (GROMACS).

Please nevigate to the “Install” or “Reinstall” tab. Then click “configurations”, “install fftw3” and “install CWBsol”. The “IET” tab will show up if these components are successfully installed.

