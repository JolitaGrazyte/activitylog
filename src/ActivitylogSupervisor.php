<?php

namespace Spatie\Activitylog;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Auth\Guard;
use Spatie\Activitylog\Handlers\BeforeHandler;
use Spatie\Activitylog\Handlers\DefaultLaravelHandler;
use Request;
use Config;
use Auth;
use Illuminate\Database\Eloquent\Model;

class ActivitylogSupervisor
{
    /**
     * @var array logHandlers
     */
    protected $logHandlers = [];

    protected $auth;

    protected $config;

    /**
     * Create the logsupervisor using a default Handler
     * Also register Laravels Log Handler if needed.
     *
     * @param \Spatie\Activitylog\Handlers\ActivitylogHandlerInterface $logHandler
     * @param \Illuminate\Config\Repository                            $config
     * @param \Illuminate\Contracts\Auth\Guard                         $auth
     */
    public function __construct(Handlers\ActivitylogHandlerInterface $logHandler, Repository $config, Guard $auth)
    {
        $this->config = $config;

        $this->logHandlers[] = $logHandler;

        if ($this->config->get('activitylog.alsoLogInDefaultLog')) {
            $this->logHandlers[] = new DefaultLaravelHandler();
        }

        $this->auth = $auth;
    }

    /* Log some activity to all registered log handlers. */
    public function log(string $text, Model $model = null, string $adjustments = '') : bool
    {
        if (!$this->shouldLogCall($text, $model)) {
            return false;
        }

        $ipAddress = Request::getClientIp();

        foreach ($this->logHandlers as $logHandler) {
            $logHandler->log($text, $model, compact('ipAddress', 'adjustments'));
        }

        return true;
    }

    /* Clean out old entries in the log. */
    public function cleanLog() : bool
    {
        foreach ($this->logHandlers as $logHandler) {
            $logHandler->cleanLog(Config::get('activitylog.deleteRecordsOlderThanMonths'));
        }

        return true;
    }

    /**
     * Normalize the user id.
     *
     * @param object|int $userId
     *
     * @return int
     */
    public function normalizeUserId($userId)
    {
        if (is_numeric($userId)) {
            return $userId;
        }

        if (is_object($userId)) {
            return $userId->id;
        }

        if ($this->auth->check()) {
            return $this->auth->user()->id;
        }

        if (is_numeric($this->config->get('activitylog.defaultUserId'))) {
            return $this->config->get('activitylog.defaultUserId');
        };

        return '';
    }

    /**
     * Determine if this call should be logged.
     *
     * @param $text
     * @param $userId
     *
     * @return bool
     */
    protected function shouldLogCall(string $text, Model $model)
    {
        $beforeHandler = $this->config->get('activitylog.beforeHandler');

        if (is_null($beforeHandler) || $beforeHandler == '') {
            return true;
        }

        return app($beforeHandler)->shouldLog($text, $model->id);
    }
}
