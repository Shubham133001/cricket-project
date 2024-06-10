<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageOption;
use App\Models\Setting;

class CreateSetting extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

     $data =    [
            ['setting' => 'bannertitle', 'value' => '<p>&lt;h1 class="text-h4 text-sm-h3 text-md-h3 text-lg-h2"&gt;Digitize your sports venue with &lt;br /&gt;&lt;span class="primary--text "&gt;Smarters Booking Management&lt;/span&gt;&lt;/h1&gt;</p>'],
            ['setting' => 'bannerbtntext', 'value' => 'Book Slot'],
            ['setting' => 'bannerbtnlink', 'value' => '/categories'],
            ['setting' => 'aboutexcerpts', 'value' => '<p>&lt;h1 class="text-h1 text-sm-h2"&gt;About us&lt;/h1&gt;</p>'],
            ['setting' => 'features', 'value' => '[{"icon":"mdi-account-check-outline","title":"Account Verification","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"},{"icon":"mdi-lifebuoy","title":"Dedicated Support","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"},{"icon":"mdi-email-open-multiple-outline","title":"Email Integration","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"},{"icon":"mdi-clock-outline","title":"Save Time","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"}]'],
            ['setting' => 'abouteTitle', 'value' => 'About Us'],
            ['setting' => 'bannerimage', 'value' => '/images/cricket.png'],
            ['setting' => 'whyusimage', 'value' => '/images/cricket.png'],
            ['setting' => 'bannerbtntext1', 'value' => 'Contect US'],
            ['setting' => 'bannerbtnlink1', 'value' => 'https://bestcricketacademy.com/contact-us/'],
            ['setting' => 'calltotitle', 'value' => '<p>&lt;div&gt;&lt;div class="text-h3"&gt;Ready to talk?&lt;/div&gt; &lt;div class="text-h3 primary--text"&gt;Our team is here to help.&lt;/div&gt;&lt;/div&gt;</p>'],
            ['setting' => 'calltobutton', 'value' => 'Contact  Us'],
            ['setting' => 'calltobtnlink', 'value' => 'https://bestcricketacademy.com/contact-us/']
        ];
        foreach ($data as $key => $value) {
            PageOption::updateOrCreate($value);
        }

        $settingdata = [
            ['setting' => 'name', 'value' => 'booking'],
            ['setting' => 'address', 'value' => 'mohali'],
            ['setting' => 'contact', 'value' => '9999999999'],
            ['setting' => 'email', 'value' => 'help@techsmarters.com'],
            ['setting' => 'logo', 'value' => '/images/logo.png'],
        ];

        foreach ($settingdata as $key => $settingvalue) {
            Setting::updateOrCreate($settingvalue);
        }
    }
}
