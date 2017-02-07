<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

// 会员
class working_record_model extends content_model
{
    function __construct ()
    {
        parent::__construct();
        $this->table = 'zy_working_record';
    }





}
