<?php

namespace cjohnson\contracts;

/**
 * Interface MachineConfigurationContract
 */
interface MachineConfigurationContract
{

    /**
     * Accessor for the states property.
     *
     * @return array
     *   The configured states of the state machine.
     */
    public function getStates(): array;

    /**
     * Accessor for the finalStates property.
     *
     * @return array
     *   The configured final states of the state machine.
     */
    public function getFinalStates(): array;

    /**
     * Accessor for the stateTransitions property.
     *
     * @return array
     *   The configured state transitions of the state machine.
     */
    public function getTransitions(): array;

    /**
     * Accessor for the input alphabet property.
     *
     * @return array
     *   The configured input alphabet of the state machine.
     */
    public function getAlphabet(): array;

    /**
     * Accessor for the defaultState property.
     *
     * @return string
     *   The configured default state of the state machine.
     */
    public function getDefaultState(): string;
}
