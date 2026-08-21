<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;

$u = App\Models\User::where('email', 'test_probe@example.com')->first();
if (!$u) {
    $u = App\Models\User::create([
        'name' => 'Test Probe',
        'email' => 'test_probe@example.com',
        'password' => Hash::make('secret1234'),
    ]);
}
Illuminate\Support\Facades\DB::table('users')
    ->where('email', 'test_probe@example.com')
    ->update(['is_admin' => 1]);

foreach (App\Models\Post::orderBy('id')->limit(2)->get(['id', 'slug']) as $p) {
    echo $p->id . '|' . $p->slug . PHP_EOL;
}
