<?php

class Trigger {
    public function actions()
    {
        return $this->hasMany(Action::class);
    }
}