<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:calculate-variation-stock')->everyFiveMinutes();
