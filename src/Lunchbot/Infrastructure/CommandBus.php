<?php

namespace Lunchbot\Infrastructure;

use RunTimeException;

class CommandBus
{
    /**
     * @var array
     */
    private $handlers;

    public function __construct(array $handlers = [])
    {
        foreach ($handlers as $handler) {
            if (!$handler instanceOf CommandHandler) {
                throw new RunTimeException(
                    sprintf('Expected object of type "CommandHandler", got type "%s"', get_class($handler))
                );
            }
        }
        $this->handlers = $handlers;
    }

    public function dispatch(Command $command)
    {
        foreach ($this->handlers as $handler) {
            $handler->handle($command);
        }
        return true;
    }

}
