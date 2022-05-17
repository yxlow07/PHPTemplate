<?php

class FrontendDisplay
{
    public function __construct($home_url)
    {
        $this->$home_url = $home_url;
    }
}