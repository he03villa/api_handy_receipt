<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:api'], 'prefix' => 'api']);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('channel-producto', function ($user) {
    return true;
});

Broadcast::channel('channel-orden', function ($user) {
    return true;
});

Broadcast::channel('channel-categoria', function ($user) {
    return true;
});