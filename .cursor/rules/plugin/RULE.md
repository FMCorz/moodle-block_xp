---
description: "This describes the essential rules for the plugin Level Up XP (block_xp)."
alwaysApply: true
---

# Coding rules

- Existing `final` methods and objects must remain `final`.
- Existing `protected` and `private` methods and properties must remain `protected` and `private`.
- Existing PHP interfaces must never be changed.
- To add features to an interface, create a new one that extends it `interface_with_foo`.
- All code must be compatible with PHP 7.4 - 8.4 (to support Moodle 4.1-5.2)

## PHP gotchas

Do not use implicit nullable arguments (Deprecated in PHP 8.4)
```
// Don't.
public function example(\foo $var = null);

// Do.
public function example(?\foo $var = null);
```

Do not use these features:
- Named arguments (PHP 8.0)
- Constructor promotion (PHP 8.0)
- `match`, the alternative to `switch` (PHP 8.0)
- `#[...]` attributes aka annotations (PHP 8.0)
- `mixed` or Union types (PHP 8.0)
- Nullsafe operators `$example?->foo?->bar` (PHP 8.0)
- The string functions `str_[contains|starts_with|ends_with]` (PHP 8.0)
