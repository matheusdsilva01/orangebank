<?php

use App\Dto\ActionDTO;
use App\Jobs\ProcessAction;
use App\Models\Goal;
use App\Models\GoalProgress;
use App\Models\User;

test('goal progress is created when action matches goal attributes', function (): void {
    // Prepare
    $goal = Goal::factory()->create([
        'name' => 'Welcome Login',
        'description' => 'Login to your account',
        'attributes' => ['action' => 'login'],
        'threshold' => 1,
    ]);
    $user = User::factory()->create();
    $actionDTO = new ActionDTO(
        entity: $user,
        attributes: ['action' => 'login']
    );

    // Act
    ProcessAction::dispatchSync($actionDTO);

    // Assert
    $this->assertDatabaseHas('goal_progress', [
        'entity_id' => $user->id,
        'entity_type' => User::class,
        'goal_id' => $goal->id,
        'progress' => 1,
        'completed' => true,  // threshold is 1
    ]);
});

test('goal progress increments on multiple matching actions', function (): void {
    // Prepare
    $goal = Goal::factory()->create([
        'name' => 'Deposit Master',
        'description' => 'Make 3 deposits',
        'attributes' => ['action' => 'deposit'],
        'threshold' => 3,
    ]);
    $user = User::factory()->create();

    // Create existing progress
    GoalProgress::factory()->create([
        'goal_id' => $goal->id,
        'entity_id' => $user->id,
        'entity_type' => User::class,
        'progress' => 1,
        'completed' => false,
    ]);

    $actionDTO = new ActionDTO(
        entity: $user,
        attributes: ['action' => 'deposit']
    );

    // Act
    ProcessAction::dispatchSync($actionDTO);

    // Assert
    $progress = GoalProgress::where([
        'goal_id' => $goal->id,
        'entity_id' => $user->id,
    ])->first();

    expect($progress->progress)->toBe(2);
    expect($progress->completed)->toBeFalse();
});

test('goal is marked completed when threshold is reached', function (): void {
    // Prepare
    $goal = Goal::factory()->create([
        'name' => 'Stock Trader',
        'description' => 'Buy 2 stocks',
        'attributes' => ['action' => 'buy_stock'],
        'threshold' => 2,
    ]);
    $user = User::factory()->create();

    GoalProgress::factory()->create([
        'goal_id' => $goal->id,
        'entity_id' => $user->id,
        'entity_type' => User::class,
        'progress' => 1,
        'completed' => false,
    ]);

    $actionDTO = new ActionDTO(
        entity: $user,
        attributes: ['action' => 'buy_stock']
    );

    // Act
    ProcessAction::dispatchSync($actionDTO);

    // Assert
    $progress = GoalProgress::where([
        'goal_id' => $goal->id,
        'entity_id' => $user->id,
    ])->first();

    expect($progress->progress)->toBe(2);
    expect($progress->completed)->toBeTrue();
});

test('different users have separate progress tracking', function (): void {
    // Prepare
    $goal = Goal::factory()->create([
        'name' => 'First Login',
        'description' => 'Login for the first time',
        'attributes' => ['action' => 'login'],
        'threshold' => 1,
    ]);
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    // Act
    ProcessAction::dispatchSync(new ActionDTO($user1, ['action' => 'login']));
    ProcessAction::dispatchSync(new ActionDTO($user2, ['action' => 'login']));

    // Assert
    $this->assertDatabaseCount('goal_progress', 2);

    $progress1 = GoalProgress::where('entity_id', $user1->id)->first();
    $progress2 = GoalProgress::where('entity_id', $user2->id)->first();

    expect($progress1->entity_id)->toBe($user1->id);
    expect($progress2->entity_id)->toBe($user2->id);
    expect($progress1->progress)->toBe(1);
    expect($progress2->progress)->toBe(1);
    expect($progress1->completed)->toBeTrue();
    expect($progress2->completed)->toBeTrue();
});
