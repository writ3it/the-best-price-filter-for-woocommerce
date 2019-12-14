<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 19.10.19
 * Time: 21:48
 */

class tbwpf_OptFloatProperties extends tbwpf_OptProperties
{
    protected $type = tbwpf_OptPropertyTypes::FLOAT;
    protected $sqlCast = 'DECIMAL(10,2)';
    protected $sqlType = 'DECIMAL(10,2) NOT NULL';


}