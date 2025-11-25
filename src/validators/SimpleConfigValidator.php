<?php
namespace cjohnson\validators;

use cjohnson\factory\StateMachineConfig;

/**
 * Class SimpleMachineConfigValidation
 *
 * This class is responsible for validating state machine configurations.
 */
class SimpleConfigValidator
{
    /**
     * Define minimum number of states required.
     */
    const MINIMUM_STATES = 1;

    /**
     * Define minimum number of elements in the input alphabet.
     */
    const MINIMUM_ALPHABET_SIZE = 2;

    /**
     * Define minimum number of transitions required.
     */
    const MINIMUM_TRANSITIONS = 1;

    /**
     * Validate the machine configuration.
     *
     * @param StateMachineConfig $config
     *   the state machine configuration to validate.
     * @return bool
     *   true if the configuration is valid, false otherwise.
     */
    public function validate(StateMachineConfig $config): bool
    {
        $states = $config->getStates();
        $finalStates = $config->getFinalStates();
        $alphabet = $config->getAlphabet();
        $transitions = $config->getTransitions();
        $defaultState = $config->getDefaultState();

        $result = true;

        if (!$this->validateStates($states)) {
            $result = false;
        }

        if (!$this->validateFinalStates($finalStates, $states)) {
            $result = false;
        }

        if (!$this->validateAlphabet($alphabet)) {
            $result = false;
        }

        if (!$this->validateTransitions($transitions)) {
            $result = false;
        }

        if (!$this->validateDefaultState($defaultState, $states)) {
            $result = false;
        }

        return $result;
    }

    /**
     * Validate the states array.
     *
     * @param array $states
     *   the array of states to validate.
     * @return bool
     *   true if the states array is valid, false otherwise.
     */
    protected function validateStates(array $states): bool
    {

        if (count($states) <= self::MINIMUM_STATES) {
            return false;
        }

        return true;
    }

    /**
     * Validate the final states array.
     *
     * @param array $finalStates
     *   the array of final states to validate.
     * @param array $states
     *   the array of all implemented states.
     * @return bool
     *   true if the final states array is valid, false otherwise.
     */
    protected function validateFinalStates(array $finalStates, array $states): bool
    {
        if (count($finalStates) <= self::MINIMUM_STATES) {
            return false;
        }

        $diff = array_diff($finalStates, $states);
        if (empty($diff)) {
            return true;
        }

        return false;
    }

    /**
     * Validate the alphabet array.
     *
     * @param array $alphabet
     *   the array of input alphabet symbols to validate.
     * @return bool
     *   true if the input alphabet array is valid, false otherwise.
     */
    protected function validateAlphabet(array $alphabet): bool
    {
        if (count($alphabet) < self::MINIMUM_ALPHABET_SIZE) {
            return false;
        }

        return true;
    }

    /**
     * Validate the transitions string.
     *
     * @param array $transitions
     *   the array of transitions to validate.
     * @return bool
     *   true if the transitions array is valid, false otherwise.
     */
    protected function validateTransitions(array $transitions): bool
    {
        if (count($transitions) < self::MINIMUM_TRANSITIONS) {
            return false;
        }
        foreach ($transitions as $item) {
            if (count($item) !== 3) {
                return false;
            }
        }
        return !empty($transitions);
    }

    /**
     * Validate the default state.
     *
     * @param string $defaultState
     *   the default state to validate.
     * @param array $states
     *   the array of all implemented states.
     * @return bool
     *   true if the default state is valid, false otherwise.
     */
    protected function validateDefaultState(string $defaultState, array $states): bool
    {
        return in_array($defaultState, $states, true);
    }
}
