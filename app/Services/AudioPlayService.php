<?php

namespace App\Services;


use App\Models\AudioPlay;
use App\Models\VoiceActor;

class AudioPlayService
{
    public function store($input){

        $new_audio_play = AudioPlay::create(['title'=>$input['title']]);

        $voice_actors_array = explode(",",$input['voice_actors']);

        //Go through each voice actor provided by user
        //Attempt to find voice actor by their name
        foreach ($voice_actors_array as $voice_actor_name){

            $saved_voice_actor = VoiceActor::where('name',$voice_actor_name)->first();

            //If voice actor isn't found by their name, then create a new voice actor
            if(!$saved_voice_actor)
                $saved_voice_actor = VoiceActor::create(['name'=>$voice_actor_name]);

            //Create relationship between audio play and found voice actor
            $new_audio_play->voice_actors()->attach($saved_voice_actor->id);
        }
    }

    public function update($audio_play, $input){

        $audio_play->title = $input['title'];
        $audio_play->save();

        //Detach all associated voice actors and reattach them
        $audio_play->voice_actors()->detach();

        $voice_actors_array = explode(",",$input['voice_actors']);

        //Go through each voice actor provided by user
        //Attempt to find voice actor by their name
        foreach ($voice_actors_array as $voice_actor_name){

            $saved_voice_actor = VoiceActor::where('name',$voice_actor_name)->first();

            //If voice actor isn't found by their name, then create a new voice actor
            if(!$saved_voice_actor)
                $saved_voice_actor = VoiceActor::create(['name'=>$voice_actor_name]);

            //Create relationship between audio play and found voice actor
            $audio_play->voice_actors()->attach($saved_voice_actor->id);
        }
    }
}
