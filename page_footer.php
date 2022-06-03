<?php

    $server = $_SERVER['HTTP_HOST'];

    echo ('<div class="container"><div class="footercontainer">');
    echo ('<font size=2>'.$software_name.(empty($server)?'':' on <a href="/" style="color:blue">'.$server.'</a> ').'(Siqin Cao, 2022)</font>');
    echo ('</div></div>');
    echo ("</body></html>")


?>
