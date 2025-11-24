# API — Public surface & Validation Guarantees

This document describes the public API, expected types, runtime behavior, and validation guarantees for the State Machine module.

## Overview

Public classes:
- `StateMachine` — runtime state machine instance produced by the factory.
- `StateMachineFactory` — builds `StateMachine` instances from a configuration contract.
- `StateMachineConfig` / `MachineConfigurationContract` — configuration wrapper / contract consumed by the factory.
- `SimpleConfigValidator` (or `SimpleMachineConfigValidation`) — stateless validator that checks configuration shape and semantics.
- Optional: any PSR-3 `Psr\Log\LoggerInterface` may be passed to the factory for diagnostics.

## Quick example

Create config, build machine, and run a transition:

    // $configArray shape
    $configArray = [
        'states' => ['S0', 'S1', 'S2'],
        'finalStates' => ['S2'],
        'alphabet' => [0, 1],
        'transitions' => [
            ['S0', 0, 'S0'],
            ['S0', 1, 'S1'],
            ['S1', 0, 'S2'],
        ],
        'defaultState' => 'S0',
    ];

    $config = new StateMachineConfig($configArray);
    $factory = new StateMachineFactory($config, $logger); // $logger optional
    $machine = $factory->build(); // returns StateMachine|null

    if ($machine === null) {
        // invalid configuration — check logs if logger provided
    } else {
        $current = $machine->getCurrentState(); // string
        $after = $machine->transitionTo(1); // returns string current state after transition
    }

## API reference (public surface)

- `StateMachineFactory::__construct(MachineConfigurationContract $config, ?Psr\Log\LoggerInterface $logger = null)`
    - Accepts configuration and optional PSR-3 logger.

- `StateMachineFactory::build(): ?StateMachine`
    - Returns a `StateMachine` when configuration is valid; returns `null` on invalid configuration. When a logger is supplied, validation failures are logged.

- `StateMachine::getCurrentState(): string`
    - Returns the current state identifier (recommended to be `string`).

- `StateMachine::transitionTo(mixed $input): string`
    - Accepts an input symbol (scalar matching items in the `alphabet`) and applies the transition rules for the current state.
    - Returns the current state after the attempted transition.
    - Unrecognized input or missing transition leaves the machine in its current state; no exception is thrown in normal operation.

- `SimpleConfigValidator::validate(MachineConfigurationContract $config): bool`
    - Returns `true` when the configuration is syntactically and semantically valid; `false` otherwise.
    - Consider extending to return diagnostics (array of messages) for richer feedback.

## Validation guarantees

When the validator returns `true` (and `build()` returns a `StateMachine`) the following invariants hold:

1. States
    - `states` is a non-empty array of unique state identifiers (prefer `string`).
2. Alphabet
    - `alphabet` is a non-empty array of symbols (strings or integers). Matching uses strict equality (`===`).
3. Final states
    - Every entry in `finalStates` exists in `states`. `finalStates` may be empty.
4. Transitions
    - Each transition is an ordered triple: `[fromState, inputSymbol, toState]` (exactly 3 elements).
    - `fromState` and `toState` exist in `states`.
    - `inputSymbol` exists in `alphabet`.
    - Malformed entries (wrong length, wrong types, unknown references) cause validation to fail.
5. Default state
    - `defaultState` is present in `states`.

## Behavior on invalid configuration

- `SimpleConfigValidator::validate()` returns `false` for any violation above.
- `StateMachineFactory::build()` returns `null` when validation fails.
- Errant behavior (exceptions, crashes) is not expected during normal operation when using a valid `StateMachine` instance.
- When a `logger` is provided, validation failures are logged with appropriate severity level.
- Rather that waiting for an application-crashing exception in runtime, watch the log for validation issues when building a machine.

## Maintenance

- Document any additional validation rules in this file and update unit tests accordingly.
- Keep method signatures and behaviors stable; if changing return types (e.g., from `null` to exceptions), update docs and tests.
