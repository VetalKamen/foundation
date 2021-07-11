<?php

class CustomRequest
{
    public $endpoint;
    public $limit;

    public function set_param_to_query($param = '', $value = ''): void
    {
        if ( ! empty($param) && is_string($param) && ! empty($value)) {
            $this->endpoint .= '&' . $param . '=' . $value;
        }
    }
}