<?php

namespace Tests\Unit\AudioPlayTest;

use App\Models\AudioPlay;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UpdateAudioPlayTest extends TestCase
{
    private $existing_audio_play, $uri;

    public function setUp() : void
    {
        parent::setUp();

        DB::table('audio_plays')->truncate();
        DB::table('voice_actors')->truncate();
        DB::table('audio_play_voice_actor')->truncate();

        //Create audio play to update
        $this->existing_audio_play = AudioPlay::create(['title'=>'Test Audio Play']);
        $this->uri = 'audio_plays/'.$this->existing_audio_play->id;
    }


    /**
     * Test if user gets an error when not providing a title
     * while updating an audio play
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function no_title_audio_play_update_test()
    {
        $this->patch($this->uri)
            ->assertSessionHasErrors(['title']);
    }


    /**
     * Test if user gets an error when not providing a single
     * voice actor while updating an audio play
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function no_voice_actors_audio_play_update_test()
    {
        $this->patch($this->uri,['title'=>'Test Audio Play'])
            ->assertSessionHasErrors(['voice_actors']);
    }


    /**
     * Test if user can update the title of the audio play
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function audio_play_title_update_test()
    {
        $new_title = 'New Test Audio Play';

        //Act
        $this->patch($this->uri,[
            'title'=>$new_title,
            'voice_actors'=>'TestVoiceActor'
            ])
            ->assertSessionDoesntHaveErrors();
        $this->existing_audio_play->refresh();

        $this->assertSame($new_title,$this->existing_audio_play->title);
    }


    /**
     * Test if user can update the voice actor of audio play
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function audio_play_voice_actors_update_test()
    {
        //Assert that there are no voice actors before audio play is updated
        $this->assertDatabaseCount('voice_actors',0);
        $this->assertDatabaseCount('audio_play_voice_actor',0);

        //Act
        $response = $this->patch($this->uri,[
            'title'=>'New Test Audio Play',
            'voice_actors'=>'TestVoiceActor'
        ])->assertSessionDoesntHaveErrors();

        //Assert that there is a new voice actor after update,
        //and it is related to the audio play
        $this->assertDatabaseCount('voice_actors',1);
        $this->assertDatabaseCount('audio_play_voice_actor',1);
    }
}
