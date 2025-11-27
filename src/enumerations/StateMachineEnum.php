<?php

declare(strict_types=1);

namespace cjohnson\enumerations;

/**
 * Enumeration StateMachineEnum
 *
 * This enum represents the states used in the state machine, used for the modulo 3 exercise.
 */
enum StateMachineEnum: int
{
    case S0 = 0;
    case S1 = 1;
    case S2 = 2;

    /**
     * Transitions to the next state based on the input.
     *
     * @param StateMachineEnum $newState
     *   The input state to transition with.
     * @return StateMachineEnum
     *   The resulting state after the transition.
     */
    public function transitionTo(StateMachineEnum $newState): StateMachineEnum
    {
        return match ($this) {
            self::S0 => match ($newState) {
                self::S1 => self::S1,
                self::S0 => self::S0,
                default => $this,
            },
            self::S1 => match ($newState) {
                self::S0 => self::S2,
                self::S1 => self::S0,
                default => $this,
            },
            self::S2 => match ($newState) {
                self::S0 => self::S1,
                self::S1 => self::S2,
                default => $this,
            },
        };
    }
}
