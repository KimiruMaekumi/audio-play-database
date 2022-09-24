<?php

namespace Database\Seeders;

use App\Models\AudioPlay;
use App\Models\VoiceActor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoiceActorsHaveManyAudioPlaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create audio play
        $new_audio_play = AudioPlay::create(['title'=>'1962. 3. Die Unsichtbaren Krallen des Dr. Mabuse']);

        $voice_actor_names = [
            'Wolf Frass',
            'Gert Günther Hoffmann',
            'Karin Dor',
            'Siegfried Lowitz',
            'Walo Lüönd',
            'Rudolf Fernau',
            'Kurd Pieritz',
            'Werner Peters',
            'Wolfgang Preiss'
        ];

        //Create relationships between audio play and its voice actors
        foreach ($voice_actor_names as $voice_actor_name){
            $new_voice_actor = VoiceActor::create(['name'=>$voice_actor_name]);
            $new_audio_play->voice_actors()->attach($new_voice_actor->id);
        }

        //Create audio play
        $new_audio_play = AudioPlay::create(['title'=>'1973. Demolition']);

        $voice_actor_names = [
            'Hans-Peter Hallwachs',
            'Gert Günther Hoffmann',
            'Arnold Marquis',
            'Susanne Tremper',
            'Ingrid van Bergen',
            'Dieter Ranspach',
            'Uta Hallant',
            'Eduard Wandrey',
            'Anneliese Römer',
            'Tanja Berg',
            'Nero Brandenburg',
            'Josef Pelz von Felinau',
            'Andreas Mannkopff',
            'Helga Krauss',
            'Hubertus Bengsch',
            'Helma von Kieseritzky',
            'Rudi Schmitt',
            'Norbert Gescher',
            'Hermann Ebeling',
            'Hans Kwiet',
            'Erna Haffner',
            'Reinhard Kolldehoff',
            'Otto Czarski',
            'Klaus Jepsen',
            'Rosi Müller',
            'Friedrich W. Bauschulte',
            'Fritz Mellinger',
            'Georg Corten',
            'Joachim Kerzel',
            'Evamaria Miner',
            'Heinz Petruo',
            'Joachim Nottke',
            'Erika Matejka',
            'Walter Tappe',
            'Eric Vaessen',
            'Michaela Pfeiffer',
            'Paul Paulschmidt',
            'Burghard Klausner',
            'Gerd Holtenau',
            'Jürgen Wegner',
            'Mei Li Pfennig',
            'Hülya Laumer',
            'Hermann Wagner',
            'Rolf Marnitz',
            'Gertie Honeck',
            'Robert Matejka'
        ];

        //Create relationships between audio play and its voice actors
        foreach ($voice_actor_names as $voice_actor_name){
            $saved_voice_actor = VoiceActor::where('name',$voice_actor_name)->first();
            if(!$saved_voice_actor) $saved_voice_actor = VoiceActor::create(['name'=>$voice_actor_name]);
            $new_audio_play->voice_actors()->attach($saved_voice_actor->id);
        }
    }
}
