<?php
/*
 * This file is part of Flarum.
 * (c) Thai Tran <tqtran@us.ibm.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flarum\Auth\IBM\Listener;

use Flarum\Event\ConfigureMiddleware;
use Illuminate\Contracts\Events\Dispatcher;

class AddAuth {

    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param SettingsRepositoryInterface $settings
     * @param Application $app
     */
    public function __construct(SettingsRepositoryInterface $settings, Application $app)
    {
        $this->settings = $settings;
        $this->app = $app;
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events) {
        $events->listen(ConfigureMiddleware::class, [$this, 'configureMiddleware']);
    }

    /**
     * @param ConfigureForumRoutes $event
     */
    public function configureMiddleware(ConfigureMiddleware $event) {
        if ($event->isForum()) {
            $event->pipe->pipe($path, $app->make('Flarum\Auth\IBM\SSOFromCookie'));
            $event->pipe->pipe($path, $app->make('Flarum\Http\Middleware\AuthenticateWithSession'));
        }
        $something = 123;
    }
}
