# Xliff Parser

This library is a simple, agnostic Xliff parser specifically written for [Matecat](https://www.matecat.com).

## Xliff Support

Xliff supported versions:

* [1.0](http://www.oasis-open.org/committees/xliff/documents/contribution-xliff-20010530.htm)
* [1.1](http://www.oasis-open.org/committees/xliff/documents/xliff-specification.htm)
* [1.2](http://docs.oasis-open.org/xliff/v1.2/os/xliff-core.html)
* [2.0](http://docs.oasis-open.org/xliff/xliff-core/v2.0/xliff-core-v2.0.html#data)

## Methods

* [xliffToArray]() - converts a xliff file into an array
* [replaceTranslation]() - replace a translation in a xliff file 

## Logging

You can inject your own logger (must be a `LoggerInterface` implementation):

```php
// ...

// $logger must be implement PS3 LoggerInterface
$parser = new XliffParser($logger);

```

## Support

If you found an issue or had an idea please refer [to this section](https://github.com/mauretto78/xliff-parser/issues).

## Authors

* **Mauro Cassani** - [github](https://github.com/mauretto78)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
