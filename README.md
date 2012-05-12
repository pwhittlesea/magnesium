Magnesium - OO TripleStore traversal
=========

This user manual (like Magnesium) is in the works.

As features improve, so will this manual...

User Guide
---------

How do I get a refernce to an object in my triple store?
```php
$magnesium = new Magnesium();

$single = $magnesium->get( "URI" );

echo $single;
```