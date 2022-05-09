<?php

foreach (glob(dirname(__DIR__) . "/php/Classes/*Utility.php") as $filename)
{
    include_once $filename;
}