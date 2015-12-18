<?php

namespace bootstrap;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 * Trait ContextProvidingTrait
 *
 * Provides access to other contexts
 */
trait ContextProvidingTrait
{
    private $contexts;

    /**
     * @BeforeScenario
     * @param BeforeScenarioScope $scope
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        foreach ($environment->getContextClasses() as $class) {
            $name = strtolower(preg_replace('~.*\\\\(\w+)Context~', '\1', $class));
            $this->contexts[$name] = $environment->getContext($class);
        }
    }
}
