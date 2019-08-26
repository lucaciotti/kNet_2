
```php
use Illuminate\Support\Facades\DB;

DB::table('orders')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
            ->crossJoin('colours')
            ->join('contacts', function ($join) {
                $join->on('users.id', '=', 'contacts.user_id')->orOn(...);
            })
            ->join('contacts', function ($join) {
                $join->on('users.id', '=', 'contacts.user_id')
                    ->where('contacts.user_id', '>', 5);
            })
            ->selectRaw('price * ? as price_with_tax', [1.0825])
            ->whereRaw('price > IF(state = "TX", ?, 100)', [200])
            ->groupBy('department')
            ->havingRaw('SUM(price) > ?', [2500])
            ->orderByRaw('updated_at - created_at DESC')
            ->get();
```