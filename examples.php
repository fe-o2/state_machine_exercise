<?php
require_once __DIR__ . '/vendor/autoload.php';

use cjohnson\builder\StateMachine;
use cjohnson\builder\StateMachineFactory;
use cjohnson\builder\StateMachineConfig;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$states = ['S0', 'S1', 'S2'];
$finalStates = ['S0', 'S1', 'S2'];
$alphabet = [0, 1];
$stateTransitions = [
    ["S0", 0, "S0"],
    ["S0", 1, "S1"],
    ["S1", 0, "S2"],
    ["S1", 1, "S0"],
    ["S2", 0, "S1"],
    ["S2", 1, "S2"],
];
$defaultState = "S0";

$machine = new StateMachine($states, $finalStates, $alphabet, $stateTransitions, $defaultState);
echo "StateMachine created using Enumeration." . PHP_EOL;
echo "Initial State: " . $machine->getCurrentState() . PHP_EOL;
$inputs = [1, 0, 0, 1];
foreach ($inputs as $input) {
    $newState = $machine->transitionTo($input);
    echo "Input: $input, New State: $newState" . PHP_EOL;
}

$config = [
    'states' => $states,
    'finalStates' => $finalStates,
    'alphabet' => $alphabet,
    'transitions' => $stateTransitions,
    'defaultState' => $defaultState,
];
$logger = new Logger('name');
$logger->pushHandler(new StreamHandler('state_machine.log', Level::Warning));

$machineConfig = new StateMachineConfig($config);
$builtMachine = (new StateMachineFactory($machineConfig, $logger))->build();
if ($builtMachine) {
    echo "StateMachine created by StateMachineFactory." . PHP_EOL;
    echo "Initial State: " . $builtMachine->getCurrentState() . PHP_EOL;
    $inputs = [1, 0, 0, 1];
    foreach ($inputs as $input) {
        $newState = $builtMachine->transitionTo($input);
        echo "Input: $input, New State: $newState" . PHP_EOL;
    }
} else {
    echo "Failed to build machine using MachineBuilder." . PHP_EOL;
}
