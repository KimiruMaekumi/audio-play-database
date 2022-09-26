<?php

namespace Tests\Unit\AudioPlayTest;

use App\Models\AudioPlay;
use App\Models\VoiceActor;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SearchAudioPlayByVoiceActorTest extends TestCase
{
    private $uri;

    public function setUp() : void
    {
        parent::setUp();

        DB::table('voice_actors')->truncate();
        DB::table('audio_play_voice_actor')->truncate();
        DB::table('audio_plays')->truncate();

        $this->uri = 'audio_plays/search';
    }


    /**
     * Test if search function returns all audio plays
     * if no search term is provided
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function all_audio_play_search_test()
    {
        $audio_plays = AudioPlay::factory()->count(5)->create();

        $response = $this->get($this->uri)
            ->assertViewHas('audio_plays');

        //Assert that view has all the plays that were created
        $this->assertEquals(
            $audio_plays->pluck('id'),
            $response->viewData('audio_plays')->pluck('id')
        );
    }


    /**
     * Test if search function returns a specific audio play
     * by providing a search term that matches the voice actors
     * name.
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function single_result_audio_play_search_test()
    {
        //Create an audio play with a voice actor and create a relationship between them
        $audio_play = AudioPlay::factory()->create();
        $voice_actor_name = 'TestVoiceActorName';
        $voice_actor = VoiceActor::factory()->create(['name'=>$voice_actor_name]);
        $audio_play->voice_actors()->attach($voice_actor);

        //Act
        $response = $this->get($this->uri.'?search_terms='.$voice_actor_name);

        //Assert that there is only one audio play in view and its id matches
        $view_audio_plays = $response->viewData('audio_plays');
        $this->assertCount(1,$view_audio_plays);
        $this->assertEquals(
            $audio_play->id,
            $view_audio_plays[0]->id
        );
    }


    /**
     * Test if search function returns a specific audio play
     * by providing a search term that is like the voice actors
     * name, but doesn't match.
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function single_result_like_audio_play_search_test()
    {
        //Create an audio play with a voice actor and create a relationship between them
        $audio_play = AudioPlay::factory()->create();
        $voice_actor_name = 'TestVoiceActorName';
        $voice_actor = VoiceActor::factory()->create(['name'=>$voice_actor_name]);
        $audio_play->voice_actors()->attach($voice_actor);

        //Act
        $response = $this->get($this->uri.'?search_terms=Voice');

        //Assert that there is only one audio play in view and its id matches
        $view_audio_plays = $response->viewData('audio_plays');
        $this->assertCount(1,$view_audio_plays);
        $this->assertEquals(
            $audio_play->id,
            $view_audio_plays[0]->id
        );
    }


    /**
     * Test if search function returns a multiple audio plays
     * by providing a search term that matches the name
     * of voice actor of the audio plays
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function multiple_results_audio_play_search_test()
    {
        //Create 3 audio plays that have the same voice actor
        $audio_plays = AudioPlay::factory()->count(3)->create();
        $voice_actor = VoiceActor::factory()->create(['name'=>'Anna']);
        foreach ($audio_plays as $audio_play){
            $audio_play->voice_actors()->attach($voice_actor);
        }

        //Create another audio play that has different voice actor
        //to test if the search function doesn't show all audio plays
        $unsearched_audio_play = AudioPlay::factory()->create();
        $different_voice_actor = VoiceActor::factory()->create(['name'=>'Bob']);
        $unsearched_audio_play->voice_actors()->attach($different_voice_actor);

        //Act
        $response = $this->get($this->uri.'?search_terms=Anna');

        //Assert that there are only 3 audio plays returned and the expected ids match
        $view_audio_plays = $response->viewData('audio_plays');
        $this->assertCount(3,$view_audio_plays);
        foreach ($audio_plays as $audio_play){
            $this->assertContains($audio_play->id,$view_audio_plays->pluck('id'));
        }
    }


    /**
     * Test if user can retrieve audio plays by
     * providing multiple search terms separated
     * by comma
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function multiple_terms_audio_play_search_test()
    {
        //Create 3 audio plays that have different voice actors
        $audio_plays = AudioPlay::factory()->count(3)->create();
        $voice_actors_names = ['Anna','Bob','Candice'];
        for ($i=0; $i<3; $i++){
            $new_voice_actor = VoiceActor::factory()->create(['name' => $voice_actors_names[$i]]);
            $audio_plays[$i]->voice_actors()->attach($new_voice_actor);
        }

        //Create another audio play that has different voice actor
        //to test if the search function doesn't show all audio plays
        $unsearched_audio_play = AudioPlay::factory()->create();
        $different_voice_actor = VoiceActor::factory()->create(['name'=>'Daniel']);
        $unsearched_audio_play->voice_actors()->attach($different_voice_actor);

        //Act
        $response = $this->get($this->uri.'?search_terms=Anna,Bob,Candice');

        //Assert that there are only 3 audio plays returned and the expected ids match
        $view_audio_plays = $response->viewData('audio_plays');
        $this->assertCount(3,$view_audio_plays);
        foreach ($audio_plays as $audio_play){
            $this->assertContains($audio_play->id,$view_audio_plays->pluck('id'));
        }
    }


    /**
     * Test if audio play search results are sorted
     * by their title.
     *
     * @return void
     * @test
     * @group audio_play
     */
    public function sort_all_audio_play_search_test()
    {
        //Create audio plays with different titles and attach the same voice actor
        $audio_play_titles = collect(['Delta','1932','Omega','Beta','Alpha',]);
        $expected_audio_play_order = collect(['1932','Alpha','Beta','Delta','Omega',]);

        $voice_actor = VoiceActor::factory()->create(['name'=>'TestVoiceActor']);
        foreach ($audio_play_titles as $audio_play_title){
            $audio_play = AudioPlay::factory()->create(['title'=>$audio_play_title]);
            $audio_play->voice_actors()->attach($voice_actor);
        }

        //Act
        $response = $this->get($this->uri.'?search_terms=TestVoiceActor');

        //Assert that returned audio plays are in alphabetical order
        $view_audio_plays = $response->viewData('audio_plays');
        $this->assertEquals($expected_audio_play_order,$view_audio_plays->pluck('title'));
    }
}
