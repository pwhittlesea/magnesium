Magnesium - OO TripleStore traversal
=========

This user manual (like Magnesium) is in the works.

As features improve, so will this manual...

User Guide
---------

How do I get a reference to an object in my triple store?
```php
// Create a new Magnesium owner
$magnesium = new Magnesium();

// Collect our Subject and Object using a predicate
$subject = $magnesium->get( "http://example.com/id/1" );
$predicate = "http://predicate.org/ont#relation";
$object = $subject->rel($predicate);

// Print out our triple
echo "<${subject}> -> <${predicate}> -> <${object}>";
```
