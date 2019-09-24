# Global Parameters

Parameters common to all instances of the template class and thus to all rendered templates can be set.
Beware, per-template params will over-write global params. There are two methods of defining them depending on whether you've instantiated an instance of the class yet or not:

On the instance:
```
$tpl->setGlobalParams(['baseurl'=>'/']);
```

Or, on the class:
```
TemplateSeed::globalParams(['baseurl'=>'/']);
```
