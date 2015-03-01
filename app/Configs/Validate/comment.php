<?php
return array(
    'names'  => array(
        'comment_text',
        'comment_username' ,
        'comment_email' ,
        'comment_header' ),
       'rules' => array (
           'required|max_len,140|min_len,6',
           'required|max_len,30|min_len,6',
           'required|valid_email',
           'required|min_len,6'
       ),
        'filters' => array(
            'trim|sanitize_string',
            'trim|base64_encode',
            'trim|sanitize_email',
            'trim'
        )
);