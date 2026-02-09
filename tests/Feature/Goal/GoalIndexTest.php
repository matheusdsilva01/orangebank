<?php

use App\Models\Goal;
use App\Models\GoalProgress;
use App\Models\User;

test('authenticated user can view goals index page', function (): void {
    // Prepare
    $user = User::factory()->create();
    $goal = Goal::factory()->create([
        'name' => 'First Login',
        'description' => 'Login for the first time',
        'attributes' => ['action' => 'login'],
        'threshold' => 1,
    ]);
    GoalProgress::factory()->create([
        'goal_id' => $goal->id,
        'entity_id' => $user->id,
        'entity_type' => User::class,
        'progress' => 1,
        'completed' => true,
    ]);

    // Act
    $this->actingAs($user);
    $response = $this->get(route('goals'));

    // Assert
    $response->assertOk();
    $response->assertViewIs('goals.index');
    $response->assertViewHas('goalProgress');
});

test('unauthenticated user is redirected to login', function (): void {
    // Act
    $response = $this->get(route('goals'));

    // Assert
    $response->assertRedirect(route('login'));
});

test('user only sees their own goal progress', function (): void {
    // Prepare
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $goal = Goal::factory()->create([
        'attributes' => ['action' => 'login'],
        'threshold' => 1,
    ]);

    $progress1 = GoalProgress::factory()->create([
        'goal_id' => $goal->id,
        'entity_id' => $user1->id,
        'entity_type' => User::class,
        'progress' => 2,
        'completed' => false,
    ]);

    $progress2 = GoalProgress::factory()->create([
        'goal_id' => $goal->id,
        'entity_id' => $user2->id,
        'entity_type' => User::class,
        'progress' => 5,
        'completed' => true,
    ]);

    // Act
    $this->actingAs($user1);
    $response = $this->get(route('goals'));

    // Assert
    $goalProgress = $response->viewData('goalProgress');
    expect($goalProgress)->toHaveCount(1);
    expect($goalProgress->first()->entity_id)->toBe($user1->id);
    expect($goalProgress->first()->progress)->toBe(2);
});

test('goal progress includes eager loaded goal relationship', function (): void {
    // Prepare
    $user = User::factory()->create();
    $goal = Goal::factory()->create([
        'name' => 'Stock Trading Master',
        'description' => 'Buy your first stock',
        'attributes' => ['action' => 'buy_stock'],
        'threshold' => 3,
    ]);
    GoalProgress::factory()->create([
        'goal_id' => $goal->id,
        'entity_id' => $user->id,
        'entity_type' => User::class,
        'progress' => 1,
        'completed' => false,
    ]);

    // Act
    $this->actingAs($user);
    $response = $this->get(route('goals'));

    // Assert
    $goalProgress = $response->viewData('goalProgress');
    expect($goalProgress->first()->relationLoaded('goal'))->toBeTrue();
    expect($goalProgress->first()->goal->name)->toBe('Stock Trading Master');
    expect($goalProgress->first()->goal->threshold)->toBe(3);
});
