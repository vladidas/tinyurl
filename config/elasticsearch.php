<?php

return [
    'hosts' => env('ELASTICSEARCH_HOST', 'http://elasticsearch:9200'),
    'username' => env('ELASTICSEARCH_USERNAME', 'elastic'),
    'password' => env('ELASTICSEARCH_PASSWORD', 'changeme'),
];
