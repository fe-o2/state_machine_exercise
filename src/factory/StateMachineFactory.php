<?php

namespace cjohnson\factory;

use cjohnson\contracts\MachineConfigurationContract;
use cjohnson\factory\StateMachine;
use cjohnson\factory\StateMachineConfig;
use cjohnson\validators\SimpleConfigValidator;
use Psr\Log\LoggerInterface;

/**
 * Class MachineBuilder
 *
 * This class is responsible for generating state machines that may be applied to application workflows.
 */
class StateMachineFactory
{
    /**
     * Class property to hold the machine configuration.
     *
     * @var StateMachineConfig
     */
    protected StateMachineConfig $machineConfig;

    /**
     * Class property to hold a logger instance.
     *
     * @var LoggerInterface|null
     */
    protected LoggerInterface $logger;

    /**
     * Default Constructor.
     *
     * @param MachineConfigurationContract $config
     *   The desired configuration of a state machine.
     * @param LoggerInterface|null $logger
     *   An optional logger for logging errors.
     */
    public function __construct(MachineConfigurationContract $config, LoggerInterface $logger = null)
    {
        $this->machineConfig = $config;
        $this->logger = $logger;
    }

    /**
     * Builds and returns a StateMachine instance if the provided configuration is valid.
     *
     * @return ?StateMachine
     *   * Logs an error and returns null if the configuration is invalid.
     */
    public function build(): ?StateMachine
    {
        if ($this->isConfigValid()) {
            $machine = new StateMachine(
                $this->machineConfig->getStates(),
                $this->machineConfig->getFinalStates(),
                $this->machineConfig->getAlphabet(),
                $this->machineConfig->getTransitions(),
                $this->machineConfig->getDefaultState()
            );
        } else {
            if ($this->logger) {
                $this->logger->error('Invalid machine configuration provided.');
            }
            return null;
            // TODO: confirm if exception is preferred over logging
            // throw new \InvalidArgumentException('Invalid machine configuration provided.');
        }

        return $machine;
    }

    /**
     * Validates the machine configuration.
     *
     * @return bool
     *   True if the configuration is valid, false otherwise.
     */
    protected function isConfigValid(): bool
    {
        $validator = new SimpleConfigValidator();
        return $validator->validate($this->getMachineConfig());
    }

    /**
     * Accessor for machine configuration.
     *
     * @return StateMachineConfig
     *   The machine configuration.
     */
    protected function getMachineConfig(): StateMachineConfig
    {
        return $this->machineConfig;
    }
}
