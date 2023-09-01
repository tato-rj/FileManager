<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Jobs\ProcessVideo;
use App\Models\Video;

class VideoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp() : void
    {
        parent::setUp();
        
        \Storage::fake('public');
        \Storage::fake('gcs');
        \Queue::fake();

        $this->setUpFaker();
    }

    /** @test */
    public function a_job_is_dispatched_when_a_user_uploads_a_video()
    {
        $this->post(route('upload'), [
            'video' => UploadedFile::fake()->image('cover.mp4'),
            'email' => $this->faker->email,
            'id' => $this->faker->randomDigit
        ]);

        \Queue::assertPushed(function (ProcessVideo $job) {
            return $job->video->is(Video::first());
        });
    }
}
