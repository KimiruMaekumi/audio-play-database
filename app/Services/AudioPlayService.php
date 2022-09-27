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
        //Convert localized characters to international versions and vice versa
        $search_terms_array = $this->international_transcriptions($search_terms_array);

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

        return [
            'audio_plays'=>AudioPlay::with('voice_actors')
                ->find($audio_play_ids),
            'search_terms'=>$search_terms_array
        ];
    }


    /**
     * Populate search terms with localized versions of international characters,
     * as well as convert localized characters to international characters.
     * Expands the search terms to include localized and international words.
     *
     * @param $search_terms
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    private function international_transcriptions($search_terms){

        $transcriptions = [
            'Ä' => 'Ae',
            'ä' => 'ae',
            'Ö' => 'Oe',
            'ö' => 'oe',
            'Ü' => 'Ue',
            'ü' => 'ue',
            'ß' => 'ss',
        ];

        $added_search_terms = [];
        $replacement_count = 1;

        foreach ($search_terms as $search_term){
            foreach ($transcriptions as $localized => $international){
                //Add localized to international conversions
                $localized_search_term = $search_term;
                while(str_contains($localized_search_term,$localized)){
                    $localized_search_term = str_replace($localized, $international,$localized_search_term,$replacement_count);
                    $added_search_terms[] = $localized_search_term;
                }
                //Add international to localized conversions
                $international_search_term = $search_term;
                while(str_contains($international_search_term,$international)){
                    $international_search_term = str_replace($international, $localized, $international_search_term, $replacement_count);
                    $added_search_terms[] = $international_search_term;
                }
            }
        }

        $search_terms = array_merge($search_terms->toArray(), $added_search_terms);

        //Combine input search terms and transcribed added search terms
        return $search_terms;
    }
}
