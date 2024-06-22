# Changelog

All notable changes to `elasticsearch-query-builder` will be documented in this file.

## 2.7.0 - 2024-05-06

### What's Changed

* Allow `bool` and `int` as types for term query by @sventendo in https://github.com/spatie/elasticsearch-query-builder/pull/34
* Fix: Include `size` and `from` in getPayload by @harlequin410 in https://github.com/spatie/elasticsearch-query-builder/pull/35
* Allow filter aggregation without using nested aggregations by @sventendo in https://github.com/spatie/elasticsearch-query-builder/pull/37

### New Contributors

* @sventendo made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/34
* @harlequin410 made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/35

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.6.0...2.7.0

## 2.6.0 - 2024-04-25

### What's Changed

* Add minimum_should_match support for BoolQuery by @srowan in https://github.com/spatie/elasticsearch-query-builder/pull/38

### New Contributors

* @srowan made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/38

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.5.0...2.6.0

## 2.5.0 - 2024-04-25

### What's Changed

* Add boost parameter to MatchQuery by @MilanLamote in https://github.com/spatie/elasticsearch-query-builder/pull/42

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.4.0...2.5.0

## 2.4.0 - 2024-04-19

### What's Changed

* Add post filter logic and enhance readme by @MilanLamote in https://github.com/spatie/elasticsearch-query-builder/pull/41

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.3.0...2.4.0

## 2.3.0 - 2024-04-17

### What's Changed

* Add highlighting by @MilanLamote in https://github.com/spatie/elasticsearch-query-builder/pull/39

### New Contributors

* @MilanLamote made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/39

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.2.0...2.3.0

## 2.2.0 - 2024-03-26

### What's Changed

* Added able track total hits by @nick-rashkevich in https://github.com/spatie/elasticsearch-query-builder/pull/36

### New Contributors

* @nick-rashkevich made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/36

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.1.0...2.2.0

## 2.1.0 - 2023-02-17

### What's Changed

- IB-1280 added sum aggregation by @webbaard in https://github.com/spatie/elasticsearch-query-builder/pull/26

### New Contributors

- @webbaard made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/26

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.0.1...2.1.0

## 2.0.1 - 2022-10-10

### What's Changed

- Fix `Builder::search()` return data type by @imdhemy in https://github.com/spatie/elasticsearch-query-builder/pull/24

### New Contributors

- @imdhemy made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/24

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/2.0.0...2.0.1

## 2.0.0 - 2022-07-22

### What's Changed

- Elasticseach ^8.0 support by @h-rafiee in https://github.com/spatie/elasticsearch-query-builder/pull/19

### New Contributors

- @h-rafiee made their first contribution in https://github.com/spatie/elasticsearch-query-builder/pull/19

**Full Changelog**: https://github.com/spatie/elasticsearch-query-builder/compare/1.4.0...2.0.0

## 1.4.0 - 2022-07-20

- add `TermsQuery`

## 1.3.0 - 2021-08-06

- add `PrefixQuery`

## 1.2.2 - 2021-07-29

- remove debug statements (again :facepalm:)

## 1.2.1 - 2021-07-29

- remove debug statements

## 1.2.0 - 2021-07-28

- add `type` to `MultiMatchQuery`

## 1.1.2 - 2021-07-22

- fix `search_after` parameter in request body

## 1.1.1 - 2021-07-22

- add `search_after` to request body

## 1.1.0 - 2021-07-22

- provide default sort order
- add `searchAfter` method

## 1.0.0 - 2021-07-07

- initial release
