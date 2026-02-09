<?php

use App\Dto\ActionDTO;
use App\Jobs\ProcessAction;
use App\Models\Action;
use App\Models\Goal;
use App\Models\GoalProgress;
use App\Models\User;

test('job creates action record in database', function (): void {
    // Prepare
    $user = User::factory()->create();
    $actionDTO = new ActionDTO(
        entity: $user,
        attributes: ['action' => 'test_action', 'details' => 'test']
    );

    // Act
    ProcessAction::dispatchSync($actionDTO);

    // Assert
    $this->assertDatabaseHas('actions', [
        'entity_id' => $user->id,
        'entity_type' => User::class,
    ]);

    $action = Action::where('entity_id', $user->id)->first();
    expect($action->attributes)->toBe(['action' => 'test_action', 'details' => 'test']);
});

test('job creates goal progress when matching goal exists', function (): void {
    // Prepare
    $goal = Goal::factory()->create([
        'name' => 'Test Goal',
        'description' => 'Test goal for job',
        'attributes' => ['action' => 'test'],
        'threshold' => 5,
    ]);
    $user = User::factory()->create();
    $actionDTO = new ActionDTO(
        entity: $user,
        attributes: ['action' => 'test']
    );

    // Act
    ProcessAction::dispatchSync($actionDTO);

    // Assert
    $this->assertDatabaseHas('goal_progress', [
        'goal_id' => $goal->id,
        'entity_id' => $user->id,
        'entity_type' => User::class,
        'progress' => 1,
        'completed' => false,
    ]);
});

test('job increments existing goal progress', function (): void {
    // Prepare
    $goal = Goal::factory()->create([
        'name' => 'Increment Test',
        'description' => 'Test increment functionality',
        'attributes' => ['action' => 'increment_test'],
        'threshold' => 10,
    ]);
    $user = User::factory()->create();

    $existingProgress = GoalProgress::factory()->create([
        'goal_id' => $goal->id,
        'entity_id' => $user->id,
        'entity_type' => User::class,
        'progress' => 5,
        'completed' => false,
    ]);

    $actionDTO = new ActionDTO(
        entity: $user,
        attributes: ['action' => 'increment_test']
    );

    // Act
    ProcessAction::dispatchSync($actionDTO);

    // Assert
    expect($existingProgress->refresh()->progress)->toBe(6);
    expect($existingProgress->completed)->toBeFalse();
});

test('job marks goal as completed when threshold is met', function (): void {
    // Prepare
    $goal = Goal::factory()->create([
        'name' => 'Complete Test',
        'description' => 'Test completion functionality',
        'attributes' => ['action' => 'complete_test'],
        'threshold' => 3,
    ]);
    $user = User::factory()->create();

    $progressRecord = GoalProgress::factory()->create([
        'goal_id' => $goal->id,
        'entity_id' => $user->id,
        'entity_type' => User::class,
        'progress' => 2,
        'completed' => false,
    ]);

    $actionDTO = new ActionDTO(
        entity: $user,
        attributes: ['action' => 'complete_test']
    );

    // Act
    ProcessAction::dispatchSync($actionDTO);

    // Assert
    $progressRecord->refresh();
    expect($progressRecord->progress)->toBe(3);
    expect($progressRecord->completed)->toBeTrue();
});
