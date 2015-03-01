<?php


  return [

      'names' => [

           'post_name',
           'post_contet',
           'post_categories',

      ],

       'rules' => [

           'required|max_len,140|min_len,6|valid_name',
           'required',
           'required|alpha_numeric'

       ],

      'filters' => [

          'trim|sanitize_string',
          'trim|',
          'trim'
      ]

  ];