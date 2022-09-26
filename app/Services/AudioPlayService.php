<?php

namespace App\Services;


use App\Models\AudioPlay;
use App\Models\VoiceActor;

class AudioPlayService
{

    /**
     * Create a new audio play using input and attach voice actors from
     * input. Creates new voice actors if the name is new in voice actor table.
     *
     * @param $input
     * @return mixed
     */
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

        return $new_audio_play;
    }


    /**
     * Update the given audio play using the user input.
     * Detaches associated voice actors and attaches using
     * the list of voice actors in the input. If a voice actor is replaced
     * and no longer related to other audio plays, then it remains in db.
     *
     * @param $audio_play
     * @param $input
     * @return mixed
     */
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

        return $audio_play;
    }


    /**
     * Find audio plays that have voice actors with a name that matches
     * or is similar to given search terms
     *
     * @param $search_terms_string
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function search_by_voice_actor($search_terms_string){

        //Split and trim search term string
        $search_terms_array = collect(explode(',',$search_terms_string));
        $search_terms_array = $search_terms_array->map(function ($item){
            return trim($item);
        });

        $audio_play_ids = collect([]);
        //Get voice actors for each search term
        foreach ($search_terms_array as $search_term){
            $voice_actors = VoiceActor::with('audio_plays')
                ->where('name','LIKE','%'.$search_term.'%')
                ->get();
            //Get audio plays for voice actor
            foreach ($voice_actors as $voice_actor){
                $audio_play_ids = $audio_play_ids
                    ->concat(
                        $voice_actor
                            ->audio_plays
                            ->pluck('id')
                    );
            }
        }
        $audio_play_ids = $audio_play_ids->unique();    //Remove duplicates

        return AudioPlay::with('voice_actors')
            ->find($audio_play_ids);
    }
}
