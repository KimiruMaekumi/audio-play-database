<?php

namespace Database\Seeders;

use App\Models\AudioPlay;
use App\Models\VoiceActor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AudioPlaysHaveManyVoiceActorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create audio play
        $new_audio_play = AudioPlay::create(['title'=>'1946. Fräulein Else']);

        $voice_actor_names = [
            'Gerd Ribatis',
            'Irmgard Weyrather',
            'Anette Roland',
            'Ernst Sladeck',
            'Ludwig Baschang',
            'Ursula Zache',
            'Lieselotte Bellert',
            'Günther Vulpius',
            'Hans Goguel',
            'Eva Fiebig',
            'Horst Uhse',
            'Karl Kempf',
            'Lothar Hartmann',
        ];

        //Create relationships between audio play and its voice actors
        foreach ($voice_actor_names as $voice_actor_name){
            $new_voice_actor = VoiceActor::create(['name'=>$voice_actor_name]);
            $new_audio_play->voice_actors()->attach($new_voice_actor->id);
        }
    }
}
