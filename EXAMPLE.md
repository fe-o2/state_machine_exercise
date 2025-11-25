# Example Laravel Installation
This example demonstrates how to integrate the state machine into a Laravel application.
This document is for example purposes only, and assumes you have a working knowledge of Composer, Laravel and Docker.

```bash
user@container:/var/www/html/state_machine_app# composer config repositories.cjohnson vcs https://github.com/johnsonoriginals/state_machine_exercise
user@container:/var/www/html/state_machine_app# composer require cjohnson/state_machine:dev-main
```
A bunch of composer activity will occur, and the package will be installed. Then we can add a new artisan command to demonstrate the state machine.
```bash
user@container:/var/www/html/state_machine_app# php artisan make:command

 ┌ What should the console command be named? ───────────────────┐
 │ CreateStateMachine                                           │
 └──────────────────────────────────────────────────────────────┘

   INFO  Console command [app/Console/Commands/CreateStateMachine.php] created successfully.  
 ```
At this point, the code needed from the state machine module is added to the command code generated above. Below is an example run of the artisan command:
 ```bash
user@container:/var/www/html/state_machine_app# php artisan app:create-state-machine 110
StateMachine created by StateMachineFactory.
Initial State: S0
Input: 1, New State: S1
Input: 1, New State: S0
Input: 0, New State: S0
Final State: S0 (0)
user@container:/var/www/html/state_machine_app# php artisan app:create-state-machine 1010
StateMachine created by StateMachineFactory.
Initial State: S0
Input: 1, New State: S1
Input: 0, New State: S2
Input: 1, New State: S2
Input: 0, New State: S1
Final State: S1 (1)

 ```
