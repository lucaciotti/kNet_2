(on branch development)$ git merge master
(resolve any merge conflicts if there are any)
git checkout master
git merge development (there won't be any conflicts now)

(on branch master)$ git merge dev
(resolve any merge conflicts if there are any)
git checkout dev
git merge master (there won't be any conflicts now)


// DEPLOY
php artisan deploy -v production