<?php

namespace Database\Seeders;

use App\Models\AudioPlay;
use App\Models\VoiceActor;
use Illuminate\Database\Seeder;
include 'AudioPlayBatches\AudioPlayBatch1.php';
include 'AudioPlayBatches\AudioPlayBatch2.php';
include 'AudioPlayBatches\AudioPlayBatch3.php';

class AudioPlayBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $audio_play_batches = [
            include 'AudioPlayBatches\AudioPlayBatch1.php',
            include 'AudioPlayBatches\AudioPlayBatch2.php',
            include 'AudioPlayBatches\AudioPlayBatch3.php'
        ];

        foreach ($audio_play_batches as $audio_play_batch){
            foreach ($audio_play_batch as $audio_play_title => $voice_actors){
                $new_audio_play = AudioPlay::create(['title'=>$audio_play_title]);
                foreach ($voice_actors as $voice_actor){

                    $saved_voice_actor = VoiceActor::where('name',$voice_actor)->first();

                    //If voice actor isn't found by their name, then create a new voice actor
                    if(!$saved_voice_actor)
                        $saved_voice_actor = VoiceActor::create(['name'=>$voice_actor]);

                    //Create relationship between audio play and found voice actor
                    $new_audio_play->voice_actors()->attach($saved_voice_actor->id);
                }
            }
        };
    }
}
