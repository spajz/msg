<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Session Keys
    |--------------------------------------------------------------------------
    |
    | Keys used for the flash messages and options
    |
    */

        'session_key' => 'msg',

    /*
    |--------------------------------------------------------------------------
    | Default Message Template
    |--------------------------------------------------------------------------
    |
    | Available templates in this package:
    | msg::custom
    | msg::bootstrap2
    | msg::bootstrap3
    | msg::foundation5
    |
    */

        'template' => 'msg::bootstrap3',

    /*
    |--------------------------------------------------------------------------
    | Message Group Template
    |--------------------------------------------------------------------------
    |
    | Set message group template.
    | array('danger' => 'msg::danger_template', 'info' => 'info_template')
    |
    */

        'templates' => array(),

    /*
    |--------------------------------------------------------------------------
    | Display Mode
    |--------------------------------------------------------------------------
    |
    | Default display mode.
    | Options: group or single
    |
    */

        'display_mode' => 'group',

    /*
    |--------------------------------------------------------------------------
    | Default Message Format
    |--------------------------------------------------------------------------
    |
    | You can use 2 placeholders:
    | :message
    | :key
    |
    */

        'format' => ':message',

    /*
    |--------------------------------------------------------------------------
    | Message Group Format
    |--------------------------------------------------------------------------
    |
    | Set custom message format by group.
    | array('danger' => '<b>:message</b>', 'info' => ':key :message')
    |
    */

        'formats' => array(),

    /*
    |--------------------------------------------------------------------------
    | Group Sorting
    |--------------------------------------------------------------------------
    |
    | Default sorting is by time added. You can use sort array to define custom
    | sorting.
    | Errors first, then others by time added: 'sort' => array('error')
    | Custom: 'sort' => array('danger', 'warning', 'success', 'custom_key')
    |
    */

        'sort_groups' => array('danger'),

    /*
    |--------------------------------------------------------------------------
    | Message Sorting
    |--------------------------------------------------------------------------
    |
    | Default sorting is by time added ascending.
    | Options
    | First element sort by: added | message
    | Second element sort type: asc | desc
    |
    */

        'sort_messages' => array('message', 'asc'),

    /*
    |--------------------------------------------------------------------------
    | Custom Message Groups
    |--------------------------------------------------------------------------
    |
    | You will be able to add and show messages from this groups directly.
    | 'custom_groups' => array('mygroup', 'group2');
    | Msg::mygroup('Hi User');
    | Msg::showMygroup();
    |
    */

        'custom_groups' => array()

);

 
 