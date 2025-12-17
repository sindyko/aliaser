# Aliaser 🎭

Elegant alias management for Laravel Eloquent models, Livewire forms, DTOs, collections, and enums. Replace long class
names with short, memorable aliases throughout your application and Livewire snapshots.

## ✨ Features

- 🎯 **Short aliases** for models, forms, DTOs, collections, and enums
- 🚀 **Entity facade** - elegant model access: `Entity::user(1)`
- 🔗 **Automatic morph map** integration
- ⚡ **Livewire integration** - up to 50% smaller snapshots
- 📦 **5 specialized registries** - organized alias management
- 🎨 **Beautiful CLI** - artisan commands with topics
- 🔒 **Security** - obfuscated class paths in frontend
- 🧪 **Fully tested** - comprehensive test coverage

## 📦 Installation

```bash
composer require sindyko/aliaser
```

Install configuration:

```bash
php artisan aliaser:install
```

## 🚀 Quick Start

### Step 1: Register Aliases

In your `AppServiceProvider::boot()`:

```php
use App\Models\{User, Post, Comment};
use App\Livewire\Forms\{PostForm, UserForm};
use App\DTOs\{UserFilterDTO, ProductDTO};
use App\Enums\{UserStatus, PostStatus};

public function boot(): void
{
    // Models (auto-syncs with morph map)
    modelsMap([
        'user' => User::class,
        'post' => Post::class,
        'comment' => Comment::class,
    ]);

    // Livewire Forms
    formsMap([
        'postForm' => PostForm::class,
        'userForm' => UserForm::class,
    ]);

    // DTOs & Value Objects
    objectsMap([
        'userFilter' => UserFilterDTO::class,
        'productDto' => ProductDTO::class,
        'money' => Money::class,
    ]);

    // Enums
    enumsMap([
        'userStatus' => UserStatus::class,
        'postStatus' => PostStatus::class,
    ]);

    // Custom Collections
    collectionsMap([
        'userCollection' => UserCollection::class,
    ]);
}
```

### Step 2: Use the Entity Facade

```php
use Sindyko\Aliaser\Facades\Entity;

// Query builder
$users = Entity::user()->where('active', true)->get();

// Find by ID
$user = Entity::user(1);

// Find with specific columns
$user = Entity::user(1, ['id', 'name', 'email']);

// Find multiple
$users = Entity::user([1, 2, 3]);

// Static methods work too!
Entity::user()->all();
Entity::user()->create(['name' => 'John']);
Entity::user()->isSoftDeletable();
```

## 📚 Complete Documentation

### Entity Facade

The `Entity` facade provides elegant access to your models:

```php
// No arguments → ModelProxy (query builder)
Entity::user()->latest()->paginate(10);

// Single ID → find()
$user = Entity::user(1); // User or null

// Array of IDs → findMany()
$users = Entity::user([1, 2, 3]); // Collection

// With columns
$user = Entity::user(1, ['id', 'name']);
$users = Entity::user([1, 2, 3], ['name', 'email']);
```

### ModelProxy Features

ModelProxy automatically handles both static and query builder methods:

```php
$user = Entity::user();

// Static methods (cached via Reflection)
$user->all();
$user->create([...]);
$user->destroy([1, 2, 3]);
$user->isSoftDeletable();
$user->isPrunable();

// Query builder methods
$user->where('active', true)->get();
$user->with('posts')->paginate();
$user->orderBy('created_at')->first();

// Get FQCN
$proxy->class(); // 'App\Models\User'

// or use
$user = Entity::user()->all();
$user = Entity::user()->create([...]);
$user = Entity::user()->destroy([1, 2, 3]);
$user = Entity::user()->isSoftDeletable();
$user = Entity::user()->isPrunable();
```

### Morph Map Integration

Model aliases automatically sync with Eloquent morph map:

```php
// Database before
commentable_type: 'App\Models\Post'
commentable_id: 1

// Database after (with Aliaser)
commentable_type: 'post'
commentable_id: 1

// Usage
$comment->commentable_type; // 'post'
```

### Livewire Integration

Aliaser automatically reduces Livewire snapshot size:

#### Models

```php
class ShowUser extends Component
{
    public User $user;
}
```

**Snapshot comparison:**

#### Without Aliaser (87 chars) + data disclosure

```json
{
    "user": [
        "mdl",
        {
            "...": "..."
        },
        {
            "class": "App\\Models\\User"
        }
    ]
}
```

#### With Aliaser (42 chars) + data is protected by an alias

```json
{
    "user": [
        "mdl-alias",
        {
            "...": "..."
        },
        {
            "class": "user"
        }
    ]
}
```

#### Forms

```php
class EditPost extends Component
{
    public PostForm $form;
}
```

**Snapshot:**

#### Before:

```json
{
    "class": "App\\Livewire\\Forms\\PostForm"
}
```

#### After:

```json
{
    "class": "postForm"
}
```

#### Collections

```php
class PostsList extends Component
{
    public Collection $posts; // Eloquent Collection
}
```

**Snapshot:**

#### Before

```json
{
    "posts": [
        "elcln",
        [
            "..."
        ],
        {
            "class": "Illuminate\\Database\\Eloquent\\Collection",
            "modelClass": "App\\Models\\Post"
        }
    ]
}
```

#### After (70% smaller!)

```json
{
    "posts": [
        "elcln-alias",
        [
            "..."
        ],
        {
            "class": "elqn_clctn",
            "modelClass": "post"
        }
    ]
}
```

**Built-in collection aliases:**

- `lrvl_clctn` → `Illuminate\Support\Collection`
- `elqn_clctn` → `Illuminate\Database\Eloquent\Collection`

#### Enums

```php
class UserProfile extends Component
{
    public UserStatus $status;
}
```

**Snapshot:**

#### Before

```json
{
    "class": "App\\Enums\\UserStatus"
}
```

#### After:

```json
{
    "class": "userStatus"
}
```

#### Objects (DTOs/Value Objects)

```php
class ProductFilter extends Component
{
    public Money $price;
    public UserFilterDTO $filters;
}
```

**Snapshot:**

#### Before

```json
{
    "price": [
        "obj",
        {
            "...": "..."
        },
        {
            "class": "App\\ValueObjects\\Money"
        }
    ],
    "filters": [
        "obj",
        {
            "...": "..."
        },
        {
            "class": "App\\DTOs\\UserFilterDTO"
        }
    ]
}
```

#### After

```json
{
    "price": [
        "obj-alias",
        {
            "...": "..."
        },
        {
            "class": "money"
        }
    ],
    "filters": [
        "obj-alias",
        {
            "...": "..."
        },
        {
            "class": "userFilter"
        }
    ]
}
```

**ObjectAliasSynth features:**

- Supports `toArray()` method
- Supports `fill()` method
- Handles uninitialized properties
- Public properties only

## 🎨 Artisan Commands

### View All Aliases

```bash
# List all registered aliases
php artisan aliaser:list

# Filter by type
php artisan aliaser:list --models
php artisan aliaser:list --forms
php artisan aliaser:list --objects
php artisan aliaser:list --collections
php artisan aliaser:list --enums

# JSON output
php artisan aliaser:list --json
```

### Get Help

```bash
# General help
php artisan aliaser:help

# Topic-specific help
php artisan aliaser:help models
php artisan aliaser:help forms
php artisan aliaser:help objects
php artisan aliaser:help collections
php artisan aliaser:help enums
php artisan aliaser:help livewire
```

## 🔧 Advanced Usage

### Programmatic Registration

```php
use Sindyko\Aliaser\Registers\ModelRegistry;

// Register single alias
ModelRegistry::register('admin', Admin::class);

// Bulk registration
ModelRegistry::map([
    'product' => Product::class,
    'order' => Order::class,
]);

// Check if exists
if (ModelRegistry::has('user')) {
    $class = ModelRegistry::find('user'); // 'App\Models\User'
}

// Get alias for class
$alias = ModelRegistry::aliasForClass(User::class); // 'user'

// Get all registered
$map = ModelRegistry::getMap(); // ['user' => 'App\Models\User', ...]

// Remove alias
ModelRegistry::forget('user');

// Clear all
ModelRegistry::clear();
```

### Allow Overwriting

```php
// Enable globally
ModelRegistry::allowOverwrite(true);
ModelRegistry::register('user', Admin::class); // OK

// Or per registration
ModelRegistry::register('user', Admin::class, $overwrite = true);
```

### Direct ModelProxy Usage

```php
use Sindyko\Aliaser\Support\ModelProxy;

$proxy = new ModelProxy(User::class);

$users = $proxy->where('active', true)->get();
$user = $proxy->find(1);
$proxy->create(['name' => 'Jane']);

// Clear static methods cache (useful in tests)
ModelProxy::clearStaticMethodsCache();
```

### Helper Functions

```php
// All helper functions support overwrite parameter
modelsMap([...], $overwrite = false);
formsMap([...], $overwrite = false);
objectsMap([...], $overwrite = false);
collectionsMap([...], $overwrite = false);
enumsMap([...], $overwrite = false);
```

## ⚙️ Configuration

```php
// config/aliaser.php
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
```

## 🏗️ Architecture

### Registries

All registries extend `AbstractAliasRegistry`:

- **ModelRegistry** - Eloquent models
- **FormRegistry** - Livewire forms
- **ObjectRegistry** - DTOs, Value Objects, Services
- **CollectionRegistry** - Custom collections
- **EnumRegistry** - Enums

**Features:**

- ✅ Bidirectional mapping (alias ↔ class)
- ✅ Reverse lookup via `aliasForClass()`
- ✅ Duplicate detection
- ✅ Overwrite protection

### Livewire Synthesizers

6 custom synthesizers for different types:

| Synthesizer                    | Key           | Handles              |
|:-------------------------------|:--------------|:---------------------|
| `ModelAliasSynth`              | `mdl-alias`   | Eloquent models      |
| `FormAliasSynth`               | `frm-alias`   | Livewire forms       |
| `ObjectAliasSynth`             | `obj-alias`   | DTOs, Value Objects  |
| `CollectionAliasSynth`         | `clctn-alias` | Custom collections   |
| `EloquentCollectionAliasSynth` | `elcln-alias` | Eloquent collections |
| `EnumAliasSynth`               | `enm-alias`   | Enums                |

**Only registered if Livewire is installed!**

### Performance

**Static Method Caching:**

```php
// ModelProxy caches Reflection results
Entity::user()->isSoftDeletable(); // ~1μs (first call - Reflection)
Entity::user()->isSoftDeletable(); // ~0.3μs (cached - 70% faster)
```

**Memory usage:**

- Registry: ~50 bytes per alias
- 100 aliases ≈ 5 KB
- 1000 aliases ≈ 50 KB

## 💡 Use Cases

```php
# 1. Multiple Models in Controller

// Before
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Tag;

public function dashboard()
{
    return [
        'users' => User::latest()->take(5)->get(),
        'posts' => Post::published()->take(10)->get(),
        'comments' => Comment::pending()->count(),
        'categories' => Category::withCount('posts')->get(),
        'tags' => Tag::popular()->get(),
    ];
}

// After
use Entity;

public function dashboard()
{
    return [
        'users' => Entity::user()->latest()->take(5)->get(),
        'posts' => Entity::post()->published()->take(10)->get(),
        'comments' => Entity::comment()->pending()->count(),
        'categories' => Entity::category()->withCount('posts')->get(),
        'tags' => Entity::tag()->popular()->get(),
    ];
}

# 2. Dynamic Model Access

// Before - switch/if or class map
public function getStats(string \$modelType)
{
    $modelClass = match($modelType) {
        'user' => \App\Models\User::class,
        'post' => \App\Models\Post::class,
        'comment' => \App\Models\Comment::class,
    };

        return $modelClass::count();
    }

// After - direct access
public function getStats(string $modelType)
{
    return Entity::$modelType()->count();
}

### 3. API Resources / Services

// Before
use App\Models\{User, Post, Comment, Like, Follow, Notification};

class UserService
{
    public function getUserData(int $userId)
    {
        return [
            'user' => User::findOrFail($userId),
            'posts' => Post::where('user_id', $userId)->get(),
            'comments' => Comment::where('user_id', $userId)->get(),
            'likes' => Like::where('user_id', \$userId)->count(),
            'followers' => Follow::where('following_id', $userId)->count(),
            'notifications' => Notification::where('user_id', $userId)->unread()->get(),
        ];
    }
}

// After
use Entity;

class UserService
{
    public function getUserData(int $userId)
    {
        return [
            'user' => Entity::user($userId),
            'posts' => Entity::post()->where('user_id', \$userId)->get(),
            'comments' => Entity::comment()->where('user_id', \$userId)->get(),
            'likes' => Entity::like()->where('user_id', \$userId)->count(),
            'followers' => Entity::follow()->where('following_id', \$userId)->count(),
            'notifications' => Entity::notification()->where('user_id', \$userId)->unread()->get(),
        ];
    }
}
```

### Refactoring Safety

```php
// Move model without database migration
// Old: App\Models\User
// New: App\Domain\Users\User

// Just update the alias registration
modelsMap(['user' => \App\Domain\Users\User::class]);

// Database morph map still uses 'user' - no migration needed!
```

### Livewire Performance

```html
<!-- Component with 10 models in state -->

<!-- Without Aliaser: 2.3 KB HTML -->
<!-- With Aliaser: 1.1 KB HTML (52% reduction!) -->

<!-- Faster initial page load -->
<!-- Less bandwidth usage -->
```

## 🔒 Security Benefits

#### Without Aliaser - exposes internal structure

```json
{
    "user": [
        "mdl",
        {
            "...": "..."
        },
        {
            "class": "App\\Domain\\Users\\Models\\User"
        }
    ],
    "settings": [
        "obj",
        {
            "...": "..."
        },
        {
            "class": "App\\Settings\\UserSettings"
        }
    ]
}
```

#### With Aliaser - obfuscated paths

```json
{
    "user": [
        "mdl-alias",
        {
            "...": "..."
        },
        {
            "class": "user"
        }
    ],
    "settings": [
        "obj-alias",
        {
            "...": "..."
        },
        {
            "class": "userSettings"
        }
    ]
}
```

Attackers can't easily determine:

- Your application structure
- Namespace organization
- Domain architecture

## 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 🔒 Security

If you discover any security issues, please email mail@sindyko.ru instead of using the issue tracker.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## 🙏 Credits

- [Alexander Kovalchuk](https://github.com/sindyko)

***

Made with ❤️ for the Laravel community

