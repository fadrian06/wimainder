<?php

app()->get('/', function () {
    response()->json(['message' => "Congrats!! You're on Leaf API"]);
});

app()->get('/debug', function () {
    response()->json([
        '$_SERVER' => $_SERVER,
        'APP_ROOT' => dirname($_SERVER['SCRIPT_FILENAME'])
    ]);
});
