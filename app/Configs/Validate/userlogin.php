<?php
return array(
       'names' => array(
        'username' ,
        'password' ,
        ),
    'rules' => array (
        'required|alpha_numeric|max_len,100|min_len,6|valid_name',
        'required|max_len,30|min_len,6',
    ),
    'filters' => array(
        'trim|sanitize_string',
        'trim|',
    )
);