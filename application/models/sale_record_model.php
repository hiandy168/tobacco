<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

// 会员
class sale_record_model extends content_model
{
    function __construct ()
    {
        parent::__construct();

        $this->table = 'zy_sale_record';
    }



}
