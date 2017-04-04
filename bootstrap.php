<?php
/*
 * This file is part of Flarum.
 * (c) Thai Tran <tqtran@us.ibm.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Flarum\Auth\IBM\Listener;
use Illuminate\Contracts\Events\Dispatcher;

return function (Dispatcher $events) {
    $events->subscribe(Listener\AddAuth::class);
};
