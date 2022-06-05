<?php

class Register
{
    public function __construct(
        public array $data = [],
        array $validate_options = []
    ){
        $this->validate($this->data);
    }

    private function validate(array $data)
    {

    }
}