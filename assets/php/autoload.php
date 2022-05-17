<?php

foreach (glob(dirname(__DIR__) . "/php/Classes/Utility/*.php") as $filename)
{
    include_once $filename;
}

foreach (glob(dirname(__DIR__) . "/php/Classes/*.php") as $filename)
{
    include_once $filename;
}