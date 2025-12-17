<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\Relation;
use Sindyko\Aliaser\Registers\ModelRegistry;
use Sindyko\Aliaser\Tests\Fixtures\Models\Post;
use Sindyko\Aliaser\Tests\Fixtures\Models\User;
use Sindyko\Aliaser\Tests\TestCase;

class MorphMapSyncTest extends TestCase
{
    /** @test */
    public function it_syncs_aliases_with_morph_map(): void
    {
        config()->set('aliaser.use_morph_map', true);

        ModelRegistry::register('user', User::class);
        ModelRegistry::register('post', Post::class);

        $map = ModelRegistry::getMap();
        Relation::enforceMorphMap($map);

        $morphMap = Relation::morphMap();

        $this->assertArrayHasKey('user', $morphMap);
        $this->assertArrayHasKey('post', $morphMap);
        $this->assertEquals(User::class, $morphMap['user']);
        $this->assertEquals(Post::class, $morphMap['post']);
    }

    /** @test */
    public function it_does_not_sync_when_disabled_in_config(): void
    {
        Relation::morphMap([], false);

        config()->set('aliaser.use_morph_map', false);

        ModelRegistry::register('user', User::class);

        $morphMap = Relation::morphMap();

        $this->assertEmpty($morphMap);
    }
}
