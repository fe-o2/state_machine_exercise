<?php

declare(strict_types=1);

namespace tests\unit\builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use cjohnson\factory\StateMachineConfig;

/**
 * Class StateMachineConfigTest
 *   Unit tests for the StateMachineConfig class.
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
            'states' => ['S0', 'S1', 'S2'],
            'finalStates' => ['S0', 'S1', 'S2'],
            'alphabet' => [0, 1],
            'stateTransitions' => [
                ["S0", 0, "S0"],
                ["S0", 1, "S1"],
                ["S1", 0, "S2"],
                ["S1", 1, "S0"],
                ["S2", 0, "S1"],
                ["S2", 1, "S2"],
            ],
            'defaultState' => "S0",
        ];

        // Act.
        $machineConfigUnderTest = new StateMachineConfig($config);

        // Assert.
        $this->assertInstanceOf(StateMachineConfig::class, $machineConfigUnderTest);
        $this->assertSame($config['states'], $machineConfigUnderTest->getStates());
        $this->assertSame($config['finalStates'], $machineConfigUnderTest->getFinalStates());
        $this->assertSame($config['alphabet'], $machineConfigUnderTest->getAlphabet());
        $this->assertSame($config['stateTransitions'], $machineConfigUnderTest->getTransitions());
        $this->assertSame('S0', $machineConfigUnderTest->getDefaultState());
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
        /** @phpstan-ignore argument.type */
        $machineUnderTest = new StateMachineConfig($testParams);
    }
}
