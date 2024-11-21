# Notes:

- `php artisan route:list` will show two routes: `api/1/nyt/best-sellers` per the assignment, and `storage/{path}`. The
  latter has to do with the built in `php artisan serve` command, though with the directory and application
  restructuring in Laravel 11, it appears to be needlessly complex to remove that secondary route for the purposes of
  the assignment.
- I wasn't sure if I was expected to match the NYT ISBN input format for my own API input format, or if the callout was
  just to identify their unique list format. I chose to use more traditional array API syntax, but the difference would
  be minimal to implement either way.
- Again, per the requirements, I used the `HTTP` facade directly rather than writing a service class to handle the
  request. I would normally expect to have some sort of library or client pattern in place to handle this and
  potentially other APIs, which would also lend to some additional test suites around the clients rather than the purely
  functional tests I have here.
- For brevity, I found the following ISBNs work to validate multiple ISBNs working as input: `1619634457` and
  `1547604174`
  reference [A Court of Thorns and Roses](https://www.amazon.com/Court-Thorns-Roses-Sarah-Maas/dp/1635575567).
