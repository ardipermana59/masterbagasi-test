<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:voucher-activation')->everyTenMinutes();
