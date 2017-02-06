<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

// 会员
class buy_record_model extends content_model
{
    function __construct ()
    {
        parent::__construct();

        $this->table = 'zy_buy_record';
    }



}
