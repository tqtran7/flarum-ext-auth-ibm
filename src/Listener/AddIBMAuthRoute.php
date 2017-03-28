<?php
/*
 * This file is part of Flarum.
 * (c) Thai Tran <tqtran@us.ibm.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flarum\Auth\IBM\Listener;

use Flarum\Event\ConfigureForumRoutes;
use Illuminate\Contracts\Events\Dispatcher;

class AddIBMAuthRoute{

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events) {
        $events->listen(ConfigureForumRoutes::class, [$this, 'configureForumRoutes']);
    }

    /**
     * @param ConfigureForumRoutes $event
     */
    public function configureForumRoutes(ConfigureForumRoutes $event) {
        $event->get('/auth/ibm', 'auth.ibm', 'Flarum\Auth\IBM\IBMAuthController');
    }
}
