<?php

namespace Tests\Feature;

use App\User;
use App\Lesson;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_without_a_plan_cannot_watch_premium_lessons()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $lesson = factory(Lesson::class)->create([ 'premium' => 1 ]);
        $lesson2 = factory(Lesson::class)->create([ 'premium' => 0 ]);

        $this->actingAs($user);

        $this->get("/series/{$lesson->series->slug}/lesson/{$lesson->id}")
            ->assertRedirect('/subscribe');
        $this->get("/series/{$lesson2->series->slug}/lesson/{$lesson2->id}")
            ->assertViewIs('watch');
    }

    public function test_a_user_on_any_plan_can_watch_all_lessons()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $lesson = factory(Lesson::class)->create([ 'premium' => 1 ]);
        $lesson2 = factory(Lesson::class)->create([ 'premium' => 0 ]);

        $this->actingAs($user);

        $this->get("/series/{$lesson->series->slug}/lesson/{$lesson->id}")
            ->assertRedirect('/subscribe');
        $this->get("/series/{$lesson2->series->slug}/lesson/{$lesson2->id}")
            ->assertViewIs('watch');

    }

    public function fakeSubscribe($user)
    {
        $user->subscriptions()->create([
            'name' => 'yearly',
            'stripe_id' => 'FAKE_STRIPE_ID',
            'stripe_plan' => 'yearly',
            'quantity' => 1
        ]);
    }
}
