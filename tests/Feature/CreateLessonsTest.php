<?php

namespace Tests\Feature;

use App\Series;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateLessonsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_lessons()
    {
        $this->withoutExceptionHandling();

        $this->loginAdmin();
        $series = factory(Series::class)->create();

        $lesson = [
            "title" => "new lesson",
            'description' => "new lesson description",
            'episode_number' => 23,
            'vimeo_id' => 2222
        ];

        $this->postJson("/admin/{$series->id}/lessons", $lesson)
            ->assertStatus(200)
            ->assertJson($lesson);

        $this->assertDatabaseHas('lessons', [
            'title' => $lesson['title']
        ]);
    }
}
