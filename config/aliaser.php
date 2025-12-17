<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Morph Map Sync
    |--------------------------------------------------------------------------
    |
    | Automatically sync model aliases with Relation::enforceMorphMap().
    |
    | Benefits:
    | - Short type names in polymorphic relations (e.g., 'post' instead of FQCN)
    | - Alias-based Livewire snapshots via getMorphClass()
    |
    | If disabled, you'll need to manually call Relation::enforceMorphMap()
    | in your AppServiceProvider if you need polymorphic relations.
    |
    */

    'use_morph_map' => env('ALIASER_USE_MORPH_MAP', true),

    /*
    |--------------------------------------------------------------------------
    | Allow Alias Overwrite
    |--------------------------------------------------------------------------
    |
    | When enabled, registering the same alias twice will overwrite
    | the previous registration instead of throwing an exception.
    |
    | Not recommended for production.
    |
    */

    'allow_overwrite' => env('ALIASER_ALLOW_OVERWRITE', false),

];
