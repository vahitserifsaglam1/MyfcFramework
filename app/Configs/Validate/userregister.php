<?php
return array(
    'names' => array(
        'username' ,
        'password' ,
        'email'   ,
        'realname' ),
    'rules' => array (
        'required|alpha_numeric|max_len,100|min_len,6|valid_name',
        'required|max_len,30|min_len,6',
        'required|valid_email',
        'required'
    ),
    'filters' => array(
        'trim|sanitize_string',
        'trim|md5',
        'trim|sanitize_email',
        'trim'
    )
);