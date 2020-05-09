<?php

use Wolif\Validate\Validator;

require_once "../vendor/autoload.php";


$input = [
    'int'       => 9,
    'int1'      => 8,
    'int2'      => 23,
    'timestamp' => time(),
    'number'    => '1',
    'string'    => 's',
    'email'     => 'xyz@example.com',
    'datetime'  => strtotime(time()),
    'datetime1' => '2020-04-01 12:34:56',
    'datetime2' => '2020-01-23 12:34:56',
    'array'     => [1, 2, 3],
    'object'    => ['a' => 1, 'b' => 2, 'c' => 3],
    'o1'        => [
        'a' => [
            1, 2, 3,
        ],
        'b' => [
            'a' => 1,
            'b' => 2,
            'c' => 3,
        ],
        'c' => [
            'a' => 11,
            'b' => 22,
            'c' => 33,
        ]
        ],
];

$validate_rule = [
    // 'int'   => [
    //     'required', //<=> ['required'],
    //     'int', //<=> ['int'],
    //     ['gte', 1],
    //     ['lte', 10],
    //     ['in', [1,2,3,4,5,6,7,8,9,10]],
    //     ['cmpGt', 'int1']
    // ], // <=>   
    'int' => 'required|int|gte:1|lte:10|in:1,2,3,4,5,6,7,8,9,10|cmpGt:int1',
    'int1' => 'required',
];


// $validate_hint = [
//     'int' => [
//         'required' => 'param [int] is necessary',
//         'int'      => 'param [int] must a int value',
//         'gte'      => 'param [int] must >= [value]',
//         'lte'      => 'param [int] must <= [value]',
//         'in'       => 'param [int] value must in [value]',
//         'cmpGt'    => 'param [int] must >= param[value]',
//     ],
// ];

Validator::v($input, $validate_rule);

print_r(Validator::lastResults());
