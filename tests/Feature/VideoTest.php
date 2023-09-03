<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Jobs\ProcessVideo;
use App\Models\{Video, User};

class VideoTest extends TestCase
{
    use WithFaker;

    public function setUp() : void
    {
        parent::setUp();
        
        \Storage::fake('public');
        \Storage::fake('gcs');
        \Queue::fake();

        $this->actingAs(User::factory()->create());

        auth()->user()->createToken('token');

        $this->videoRequest = [
            'secret' => auth()->user()->tokens->first()->name,
            'video' => UploadedFile::fake()->image('cover.mp4'),
            'email' => $this->faker->email,
            'id' => $this->faker->randomDigit
        ];

        $this->setUpFaker();
    }

    /** @test */
    public function a_job_is_dispatched_when_a_user_uploads_a_video()
    {
        $this->post(route('upload'), $this->videoRequest);

        \Queue::assertPushed(function (ProcessVideo $job) {
            return $job->video->is(Video::first());
        });
    }

    /** @test */
    public function api_requests_need_authentication()
    {
        $this->expectException('Illuminate\Auth\Access\AuthorizationException');

        array_shift($this->videoRequest);

        $this->post(route('upload'), $this->videoRequest);
    }
}
