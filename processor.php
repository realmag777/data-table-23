<?php
//этот код эмуляция взаимодействия с бэкендом
//data base emulation
$data = [
    [
        'pid' => 1,
        'id' => 1,
        'name' => 'Ivan Ivanovich',
        'gallery' => [
            'https://dev.posts-table.com/wp-content/uploads/2020/10/dbd636f3-a7d0-3427-8812-fb4eeb7e11cd.jpg',
            'https://dev.posts-table.com/wp-content/uploads/2020/10/70ca328e-b4af-304b-9931-592f28eca257.jpg'
        ],
        'match' => 40,
        'action' => 0,
        'action2' => 11,
    ],
    [
        'pid' => 2,
        'id' => 2,
        'name' => 'Petr Petrovich',
        'gallery' => [
            'https://dev.posts-table.com/wp-content/uploads/2020/10/51ff0a90-7dcc-3d31-9742-39275e6288a4.jpg',
            'https://dev.posts-table.com/wp-content/uploads/2020/10/cb9a30bc-14fa-31c6-b4f1-cbbc9b884508.jpg'
        ],
        'match' => 90,
        'action' => 0,
        'action2' => 0,
    ],
    [
        'pid' => 3,
        'id' => 3,
        'name' => 'Lorem Ipsum',
        'gallery' => [
            'https://dev.posts-table.com/wp-content/uploads/2020/10/70ca328e-b4af-304b-9931-592f28eca257.jpg',
            'https://dev.posts-table.com/wp-content/uploads/2020/10/cb9a30bc-14fa-31c6-b4f1-cbbc9b884508.jpg'
        ],
        'match' => 65,
        'action' => 0,
        'action2' => 0,
    ],
    [
        'pid' => 4,
        'id' => 4,
        'name' => 'Hello World',
        'gallery' => [
            'https://dev.posts-table.com/wp-content/uploads/2020/10/cb9a30bc-14fa-31c6-b4f1-cbbc9b884508.jpg',
            'https://dev.posts-table.com/wp-content/uploads/2020/10/70ca328e-b4af-304b-9931-592f28eca257.jpg'
        ],
        'match' => 74,
        'action' => 0,
        'action2' => 0,
    ],
];

//Data request manipulating (ordering, filtering)
if (isset($_REQUEST['orderby'])) {
    uasort($data, function($a, $b) {
        if ($a['match'] === $b['match']) {
            return 0;
        }


        if ($_REQUEST['order'] === 'asc') {
            return ($a['match'] < $b['match']) ? 0 : 1;
        }

        return ($a['match'] < $b['match']) ? 1 : 0;
    });
}

if (isset($_REQUEST['filter_data'])) {
    $_REQUEST['filter_data'] = json_decode($_REQUEST['filter_data'], true);
}


if (isset($_REQUEST['filter_data']['name']) AND!empty($_REQUEST['filter_data']['name'])) {
    foreach ($data as $key => $value) {
        if (!str_contains(strtolower($value['name']), strtolower($_REQUEST['filter_data']['name']))) {
            unset($data[$key]);
        }
    }
}


if (isset($_REQUEST['action']) AND $_REQUEST['action'] === 'is_checked') {
    //here set user as checked/unchecked by $_REQUEST['value'] AND $_REQUEST['id']
}

//+++

$out = [
    'rows' => array_values($data),
    'count' => count($data)
];

die(json_encode($out));
