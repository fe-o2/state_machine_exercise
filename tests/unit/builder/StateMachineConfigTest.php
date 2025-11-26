<?php

namespace Tests\unit\builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use cjohnson\factory\StateMachineConfig;

/**
 * Class StateMachineConfigTest
 *   Unit tests for the StateMachineConfig class.
 *
 */
#[CoversClass(\cjohnson\factory\StateMachineConfig::class)]
final class StateMachineConfigTest extends TestCase
{
    /**
     * Test constructing StateMachineConfig with full configuration.
     *
     * @return void
     */
    public function testConstructWithFullConfig(): void
    {
        // Arrange.
        $config = [
            'states' => ['idle', 'running', 'stopped'],
            'finalStates' => ['stopped'],
            'alphabet' => ['start', 'stop'],
            'transitions' => [
                'idle' => ['start' => 'running'],
                'running' => ['stop' => 'stopped'],
            ],
            'defaultState' => 'idle',
        ];

        // Act.
        $machineConfigUnderTest = new StateMachineConfig($config);

        // Assert.
        $this->assertInstanceOf(StateMachineConfig::class, $machineConfigUnderTest);
        $this->assertSame($config['states'], $machineConfigUnderTest->getStates());
        $this->assertSame($config['finalStates'], $machineConfigUnderTest->getFinalStates());
        $this->assertSame($config['alphabet'], $machineConfigUnderTest->getAlphabet());
        $this->assertSame($config['transitions'], $machineConfigUnderTest->getTransitions());
        $this->assertSame('idle', $machineConfigUnderTest->getDefaultState());
    }

    /**
     * Test constructing StateMachineConfig with empty configuration.
     * @return void
     */
    public function testConstructWithEmptyConfig(): void
    {
        // Act.
        $machineConfigUnderTest = new StateMachineConfig([]);

        // Assert.
        $this->assertSame([], $machineConfigUnderTest->getStates());
        $this->assertSame([], $machineConfigUnderTest->getFinalStates());
        $this->assertSame([], $machineConfigUnderTest->getAlphabet());
        $this->assertSame([], $machineConfigUnderTest->getTransitions());
        $this->assertSame('', $machineConfigUnderTest->getDefaultState());
    }

    /**
     * Test that the StateMachine constructor fails with invalid parameters.
     *
     * @return void
     */
    public function testStateMachineConfigConstructorFailure(): void
    {
        // Arrange.
        $testParams = -1;

        // Act & Assert.
        $this->expectException(\TypeError::class);
        $machineUnderTest = new StateMachineConfig($testParams);
    }
}
