<?php

return [

    /**
     * 自定义路由规则
     */
    'useRule' => [
        '/index\/find/' => 'hello',
        '/index\/find\/{int}/' => '/index/get?id={1}&uid={2}',
        '/index\/find\/{int}\/{int}/' => '/index/find?id={1}&uid={2}',
        '/index\/find\/{float}\/{int}/' => '/index/find?id={1}&uid={2}&name=d',
        '/index\/find\/{string}/' => '/index/find?keyword={1}',
    ],

    /**
     * 快捷匹配类型默认值
     */
    'Acter' => [
        'int' => '([1-9]\d*)',
        'string' => '(\w+)',
        'float' => '([1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0)',
        'ip' => '(\d+\.\d+\.\d+\.\d+)',
        'email' => '(\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*)',
    ]
];