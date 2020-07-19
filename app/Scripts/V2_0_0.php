<?php

namespace FleetCart\Scripts;

use Illuminate\Support\Facades\DB;

class V2_0_0
{
    public function run()
    {
        DB::delete("DELETE FROM `settings` WHERE `key` LIKE 'storefront_%' AND NOT `key` = 'storefront_copyright_text'");
        DB::delete("DELETE FROM `translations` WHERE `key` LIKE 'storefront::%'");
    }
}
