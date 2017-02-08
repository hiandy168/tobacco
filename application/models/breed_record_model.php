<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

// 会员
class breed_record_model extends content_model
{
    function __construct ()
    {
        parent::__construct();

        $this->table = 'zy_breed_record';
    }





}
