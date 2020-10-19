<?php

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(Faq::class, 10)->create()->each(function ($faq) {
      $faq->save();
    });
  }
}
