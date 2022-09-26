<?php
    header("Content-type: text/css");

    $font_family = 'Arial, Helvetica, sans-serif';
    $font_size = '2.0em';
    $border = '1px solid';
?>

table 
{
    margin: 8px;
}

th 
{
    font-family: <?=$font_family?>;
    font-size: <?=$font_size?>;
    background: #6D6D6D;
    color: #FFF;
    padding: 2px 6px;
    border-collapse: separate;
    border: <?=$border?> #000;
}

td 
{
    font-family: <?=$font_family?>;
    font-size: <?=$font_size?>;
    border: <?=$border?> #DDD;
}

tr:nth-child(even) 
{
    background-color: #e3e3e3;
}

tr:nth-child(even) > td 
{
    border-right: 1px solid lightblue;
}

tr:nth-child(odd) 
{
    background-color: lightblue;
}

tr:nth-child(odd) > td 
{
    border-right: 1px solid #e3e3e3;
}