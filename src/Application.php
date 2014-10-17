<?php

namespace RgpJones\Lunchbot;

use Pimple\Container;
use Silex\Application as BaseApplication;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RgpJones\Lunchbot\Dispatcher\Slack;

class Application extends BaseApplication
{
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $app = $this;
        $app['config'] = $values['config'];

        $app['rota_manager'] = function () use ($app) {
            return new RotaManager($app['storage']);
        };

        $app['storage'] = function () use ($values) {
            return new Storage($values['storage_file']);
        };

        $app['dispatcher'] = $this->getDispatcher($app);

        $app->register(new CommandProvider);

        $app->post('/', function (Request $request) use ($app) {

                $argv = explode(' ', trim($request->get('text')));
                $commandName = strtolower(array_shift($argv));

                /** @var Command $command */
                $command = $app['commands']->offsetExists($commandName)
                    ? $app['commands'][$commandName]
                    : $app['commands']['help'];

                $response = $command->run($argv, $request->get('user_name'));

                return new Response($response);
            }
        );
    }

    protected function getArgs()
    {

    }

    protected function getDispatcher($app)
    {
        function () use ($app) {
            return new Slack($app['config'], $app['debug']);
        };
    }
}
