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

        $data = [
            ['setting' => 'bannertitle', 'value' => '<p><span style="color: rgb(56, 56, 56);">&lt;</span><span style="color: rgb(128, 0, 0);">h1</span> <span style="color: rgb(255, 0, 0);">class</span><span style="color: rgb(56, 56, 56);">=</span><span style="color: rgb(0, 0, 255);">"text-h4 text-sm-h3 text-md-h3 text-lg-h2"</span><span style="color: rgb(56, 56, 56);">&gt;</span>Digitize your sports venue</p><h1 class="text-h4 text-sm-h3 text-md-h3 text-lg-h2">                with<span style="color: rgb(56, 56, 56);">&lt;</span><span style="color: rgb(128, 0, 0);">br</span> <span style="color: rgb(56, 56, 56);">/&gt;&lt;</span><span style="color: rgb(128, 0, 0);">span</span> <span style="color: rgb(255, 0, 0);">class</span><span style="color: rgb(56, 56, 56);">=</span><span style="color: rgb(0, 0, 255);">"primary--text "</span><span style="color: rgb(56, 56, 56);">&gt;</span>Smarters Booking Management<span style="color: rgb(56, 56, 56);">&lt;/</span><span style="color: rgb(128, 0, 0);">span</span><span style="color: rgb(56, 56, 56);">&gt;&lt;/</span><span style="color: rgb(128, 0, 0);">h1</span><span style="color: rgb(56, 56, 56);">&gt;</span></h1>'],
            ['setting' => 'bannerbtntext', 'value' => 'Book Slot'],
            ['setting' => 'bannerbtnlink', 'value' => '/categories'],
            ['setting' => 'aboutexcerpts', 'value' => '<p><span style="color: rgb(128, 128, 128);">&lt;</span><span style="color: rgb(86, 156, 214);">p</span> <span style="color: rgb(156, 220, 254);">class</span>=<span style="color: rgb(206, 145, 120);">"text--primary"</span><span style="color: rgb(128, 128, 128);">&gt;</span>We are a team of talented professionals who are passionate about</p><h1>          <span style="color: rgb(128, 128, 128);">&lt;/</span><span style="color: rgb(86, 156, 214);">p</span><span style="color: rgb(128, 128, 128);">&gt;</span></h1>'],
            ['setting' => 'features', 'value' => '[{"icon":"mdi-account-check-outline","title":"Account Verification","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"},{"icon":"mdi-lifebuoy","title":"Dedicated Support","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"},{"icon":"mdi-email-open-multiple-outline","title":"Email Integration","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"},{"icon":"mdi-clock-outline","title":"Save Time","description":"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"}]'],
            ['setting' => 'action', 'value' => 'savesettings'],
            ['setting' => 'bannerimage', 'value' => '/storage/uploads/cricket.png'],
            ['setting' => 'whyusimage', 'value' => '/storage/uploads/cricket.png'],
            ['setting' => 'bannerbtntext1', 'value' => 'Contect US'],
            ['setting' => 'bannerbtnlink1', 'value' => 'https://bestcricketacademy.com/contact-us/'],
            ['setting' => 'calltotitle', 'value' => '<p>&lt;h1&gt;Best Cricket Academy&lt;/h1&gt;</p>'],
            ['setting' => 'calltobutton', 'value' => 'Contact  Sales'],
            ['setting' => 'calltobtnlink1', 'value' => 'https://bestcricketacademy.com/contact-us/'],
            ['setting' => 'bannerimage', 'value' => 'abouteTitle'],
        ];
        foreach ($data as $key => $value) {
            PageOption::create($value);
        }

        $settingdata = [
            ['setting' => 'name', 'value' => 'booking'],
            ['setting' => 'address', 'value' => 'mohali'],
            ['setting' => 'contact', 'value' => '9999999999'],
            ['setting' => 'email', 'value' => 'help@techsmarters.com'],
            ['setting' => 'logo', 'value' => '/images/logo.png'],
        ];

        foreach ($settingdata as $key => $settingvalue) {
            Setting::create($settingvalue);
        }
    }
}
