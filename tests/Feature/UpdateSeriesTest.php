<?php

namespace Tests\Feature;

use App\Series;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateSeriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_update_a_series() {

        $this->withoutExceptionHandling();

        $this->loginAdmin();

        Storage::fake(config('filesystems.default'));

        $series = factory(Series::class)->create();

        $this->put(route('series.update', $series->slug), [
            'title' => 'new series title',
            'description' => 'new series description',
            'image' => UploadedFile::fake()->image('image-series.png')
        ])->assertRedirect(route('series.index'))
        ->assertSessionHas('success', 'Successfully updated series');

        Storage::disk(config('filesystems.default'))->assertExists(
            'public/series/' . str_slug('new series title') . '.png'
        );

        $this->assertDatabaseHas('series', [
            'slug' => str_slug('new series title'),
            'image_url' => 'series/new-series-title.png'
        ]);
    }

    public function test_an_image_is_not_required_to_update_a_series() {

        $this->withoutExceptionHandling();

        $this->loginAdmin();

        Storage::fake(config('filesystems.default'));

        $series = factory(Series::class)->create();

        $this->put(route('series.update', $series->slug), [
            'title' => 'new series title',
            'description' => 'new series description',
            // 'image' => UploadedFile::fake()->image('image-series.png')
        ])->assertRedirect(route('series.index'))
        ->assertSessionHas('success', 'Successfully updated series');

        Storage::disk(config('filesystems.default'))->assertMissing(
            'public/series/' . str_slug('new series title') . '.png'
        );

        $this->assertDatabaseHas('series', [
            'slug' => str_slug('new series title'),
            'image_url' => $series->image_url
        ]);
    }
}
