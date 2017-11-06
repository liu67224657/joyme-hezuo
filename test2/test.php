<?php

$file = fopen('a.txt','a');

for($i=0;$i<=1000;$i++){
    fwrite($file,'test--'.$i);
}
fclose($file);