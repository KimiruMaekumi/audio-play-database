<?php

namespace Tests\Unit\AudioPlayTest;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StoreAudioPlayTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();

        DB::table('audio_plays')->truncate();
    }


    /**
     * Test if user gets an error when not providing a title
     * for the new audio play
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function no_title_audio_play_store_test()
    {
        $response = $this->post('audio_plays')
            ->assertSessionHasErrors(['title']);
    }


    /**
     * Test if user gets an error when not providing a single
     * voice actor for the new audio play
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function no_voice_actors_audio_play_store_test()
    {
        $this->post('audio_plays',['title'=>'Test Audio Play'])
            ->assertSessionHasErrors(['voice_actors']);
    }


    /**
     * Test if user can successfully create audio play,
     * when providing both the title and at least one
     * voice actor
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function audio_play_store_test()
    {
        //There are no audio plays before storing a new audio play
        $this->assertDatabaseCount('audio_plays',0);

        //Act
        $this->post('audio_plays',[
            'title'=>'Test Audio Play',
            'voice_actors'=>'TestVoiceActor'
            ])
            ->assertSessionDoesntHaveErrors();

        //There is one audio play after storing a new audio play
        $this->assertDatabaseCount('audio_plays',1);
    }
}
