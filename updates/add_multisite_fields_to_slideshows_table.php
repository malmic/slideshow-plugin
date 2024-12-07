<?php

namespace Flosch\Slideshow\Updates;

use DB;
use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

return new class extends Migration
{
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('flosch_slideshow_slides')->truncate();
        DB::table('flosch_slideshow_slideshows')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        Schema::table('flosch_slideshow_slideshows', function (Blueprint $table) {
            $table->integer('site_id')->nullable()->index();
            $table->integer('site_root_id')->nullable()->index();
        });
        
        Schema::table('flosch_slideshow_slides', function (Blueprint $table) {
            $table->string('slide_image')->nullable();
            $table->string('slide_image_caption')->nullable();
        });
        
        // migrate slideshows
        $slideshows = DB::connection('octobercmsv1')->select('select * from flosch_slideshow_slideshows');
        
        foreach($slideshows as $slideshow) {
            $migratedSlideshow = DB::connection('mysql')->table('flosch_slideshow_slideshows')->insert([
                'id' => $slideshow->id,
                'name' => $slideshow->name,
                'created_at' => $slideshow->created_at,
                'updated_at' => $slideshow->updated_at,
                'site_id' => $slideshow->domain_id,
            ]);
        }
        
        // migrate slides
        $slides = DB::connection('octobercmsv1')->select('select * from flosch_slideshow_slides');
        
        foreach($slides as $slide) {
            $migratedSlide = DB::connection('mysql')->table('flosch_slideshow_slides')->insert(get_object_vars($slide));
        }
    }
    
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('flosch_slideshow_slides')->truncate();
        DB::table('flosch_slideshow_slideshows')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        Schema::table('flosch_slideshow_slides', function (Blueprint $table) {
            $table->dropColumn(['slide_image', 'slide_image_caption']);
        });
        
        Schema::table('flosch_slideshow_slideshows', function (Blueprint $table) {
            $table->dropColumn(['site_id', 'site_root_id']);
        });
    }
};