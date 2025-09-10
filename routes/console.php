<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:calculate-variation-stock')->everyFiveMinutes();
Schedule::command('app:calculate-variation-fixed-income')->everyFiveMinutes();
