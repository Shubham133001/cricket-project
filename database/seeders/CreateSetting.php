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
            ['setting' => 'bannertitle', 'value' => '<h1 class="text-h4 text-sm-h3 text-md-h3 text-lg-h2">Digitize your sports venue with<br /><span class="primary--text ">Smarters Booking Management</span></h1>'],
            ['setting' => 'bannerbtntext', 'value' => 'Book Slot'],
            ['setting' => 'bannerbtnlink', 'value' => '/categories'],
            ['setting' => 'aboutexcerpts', 'value' => '<h1 class="text-h1 text-sm-h2">About us</h1>'],
            ['setting' => 'features', 'value' => '[{"icon":"mdi-account-check-outline","title":"Account Verification","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"},{"icon":"mdi-lifebuoy","title":"Dedicated Support","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"},{"icon":"mdi-email-open-multiple-outline","title":"Email Integration","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"},{"icon":"mdi-clock-outline","title":"Save Time","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"}]'],
            ['setting' => 'action', 'value' => 'savesettings'],
            ['setting' => 'bannerimage', 'value' => '/storage/uploads/cricket.png'],
            ['setting' => 'whyusimage', 'value' => '/storage/uploads/cricket.png'],
            ['setting' => 'bannerbtntext1', 'value' => 'Contect US'],
            ['setting' => 'bannerbtnlink1', 'value' => 'https://bestcricketacademy.com/contact-us/'],
            ['setting' => 'calltotitle', 'value' => '<div><div class="text-h3">Ready to talk?</div> <div class="text-h3 primary--text">Our team is here to help.</div></div>'],
            ['setting' => 'calltobutton', 'value' => 'Contact  Us'],
            ['setting' => 'calltobtnlink1', 'value' => 'https://bestcricketacademy.com/contact-us/'],
            ['setting' => 'bannerimage', 'value' => 'abouteTitle'],
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
