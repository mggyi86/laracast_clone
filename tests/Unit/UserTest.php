<?php

namespace Tests\Unit;

use App\User;
use App\Lesson;
use App\Series;
use Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_complete_a_lesson()
    {
        $this->flushRedis();

        $user = factory(User::class)->create();

        // $series = factory(Series::class)->create();
        $lesson = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([
            'series_id' => 1
        ]);

        $user->completeLesson($lesson);
        $user->completeLesson($lesson2);

        $this->assertEquals(
            Redis::smembers('user:1:series:1'),
            [1, 2]
        );

        $this->assertEquals(
          $user->getNumberOfCompletedLessonsForASeries($lesson->series),
          2
        );
    }

    public function test_can_get_percentage_completed_for_series_for_a_user()
    {
        $this->flushRedis();

        $user = factory(User::class)->create();

        // $series = factory(Series::class)->create();
        $lesson = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([
            'series_id' => 1
        ]);
        factory(Lesson::class)->create(['series_id' => 1]);
        factory(Lesson::class)->create(['series_id' => 1]);

        $user->completeLesson($lesson);
        $user->completeLesson($lesson2);

        $this->assertEquals(
            $user->percentageCompletedForSeries($lesson->series),
            50
        );
    }

    public function test_can_know_if_a_user_has_started_a_series()
    {
        $this->flushRedis();

        $user = factory(User::class)->create();

        // $series = factory(Series::class)->create();
        $lesson = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([
            'series_id' => 1
        ]);
        $lesson3 = factory(Lesson::class)->create();

        $user->completeLesson($lesson2);

        $this->assertTrue($user->hasStartedSeries($lesson->series));
        $this->assertFalse($user->hasStartedSeries($lesson3->series));
    }

    public function test_can_get_completed_lessons_for_a_series()
    {
        $this->flushRedis();

        $user = factory(User::class)->create();

        // $series = factory(Series::class)->create();
        $lesson = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([
            'series_id' => 1
        ]);
        $lesson3 = factory(Lesson::class)->create([
            'series_id' => 1
        ]);

        $user->completeLesson($lesson);
        $user->completeLesson($lesson2);

        $completedLessons = $user->getCompletedLessons($lesson->series);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $completedLessons);
        $this->assertInstanceOf(Lesson::class, $completedLessons->random());
        $completedLessonsIds = $completedLessons->pluck('id')->all();
        // $this->assertTrue($completedLessons->contains($lesson));
        $this->assertTrue(in_array($lesson->id, $completedLessonsIds));
        $this->assertTrue(in_array($lesson2->id, $completedLessonsIds));
        $this->assertFalse(in_array($lesson3->id, $completedLessonsIds));
    }

    public function test_can_check_if_user_has_completed_lesson()
    {
        $this->flushRedis();

        $user = factory(User::class)->create();

        $lesson = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([
            'series_id' => 1
        ]);

        $user->completeLesson($lesson);

        $this->assertTrue($user->hasCompletedLesson($lesson));
        $this->assertFalse($user->hasCompletedLesson($lesson2));
    }

    public function test_can_get_all_series_being_watched_by_user() {

        $this->flushRedis();

        $user = factory(User::class)->create();

        $lesson = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([ 'series_id' => 1 ]);
        $lesson3 = factory(Lesson::class)->create();
        $lesson4 = factory(Lesson::class)->create([ 'series_id' => 2 ]);
        $lesson5 = factory(Lesson::class)->create();
        $lesson6 = factory(Lesson::class)->create([ 'series_id' => 3 ]);
        // complete lesson 1 , 2
        $user->completeLesson($lesson);
        $user->completeLesson($lesson3);

        $startedSeries = $user->seriesBeingWatched();
        // collection of series
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $startedSeries);
        $this->assertInstanceOf(Series::class, $startedSeries->random());
        $idsOfStartedSeries = $startedSeries->pluck('id')->all();

        $this->assertTrue(
            in_array($lesson->series->id, $idsOfStartedSeries)
        );
        $this->assertTrue(
            in_array($lesson3->series->id, $idsOfStartedSeries)
        );
        $this->assertFalse(
            in_array($lesson6->series->id, $idsOfStartedSeries)
        );
        //assert 1 , 2
        // assert 3
    }

    public function test_can_get_number_of_completed_lessons_for_a_user()
    {
        $this->flushRedis();

        $user = factory(User::class)->create();

        $lesson = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([ 'series_id' => 1 ]);
        $lesson3 = factory(Lesson::class)->create();
        $lesson4 = factory(Lesson::class)->create([ 'series_id' => 2 ]);
        $lesson5 = factory(Lesson::class)->create([ 'series_id' => 2 ]);

        $user->completeLesson($lesson);
        $user->completeLesson($lesson3);
        $user->completeLesson($lesson5);

        $this->assertEquals(3, $user->getTotalNumberOfCompletedLessons());
    }
}
