<?php

return [
    'title' => '系统日志',
    'severity' => [
        '0' => '紧急',
        '1' => '警报',
        '2' => '严重',
        '3' => '错误',
        '4' => '警告',
        '5' => '通知',
        '6' => '信息',
        '7' => '调试',
    ],
    'facility' => [
        '0' => '内核消息',
        '1' => '用户级消息',
        '2' => '邮件系统',
        '3' => '系统守护进程',
        '4' => '安全/授权消息',
        '5' => 'syslogd 内部生成的消息',
        '6' => '行式打印机子系统',
        '7' => '网络新闻子系统',
        '8' => 'UUCP 子系统',
        '9' => '时钟守护进程',
        '10' => '安全/授权消息',
        '11' => 'FTP 守护进程',
        '12' => 'NTP 子系统',
        '13' => '日志审计',
        '14' => '日志警告',
        '15' => '时钟守护进程（注意2）',
        '16' => '本地使用 0  (local0)',
        '17' => '本地使用 1  (local1)',
        '18' => '本地使用 2  (local2)',
        '19' => '本地使用 3  (local3)',
        '20' => '本地使用 4  (local4)',
        '21' => '本地使用 5  (local5)',
        '22' => '本地使用 6  (local6)',
        '23' => '本地使用 7  (local7)',
    ],
];
