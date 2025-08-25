<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('tokens:prune')->dailyAt('02:00');
