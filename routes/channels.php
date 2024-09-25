<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Tâche;

Broadcast::channel('tâche.{id}', function ($user, $id) {
    return $user->id === Tâche::find($id)->assigné_a;
});


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
