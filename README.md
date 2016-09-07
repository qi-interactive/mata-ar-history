MATA Active Record History
==========================================

Provides convenient method for versioning AR models.

Installation
------------

- Add the module using composer:

```json
"mata/mata-ar-history": "~1.0.0"
```

Changelog
---------

## 1.0.2.7-alpha, September 7, 2016

- Added migration (alter DocumentId from 64 to 128 characters)

## 1.0.2.6-alpha, July 12, 2016

- Fix for HistoryBehavior, added setting model attributes from revision

## 1.0.2.5-alpha, November 30, 2015

- Bug fix

## 1.0.2.4-alpha, November 2, 2015

- Fixed switch between revisions

## 1.0.2.3-alpha, October 12, 2015

- Updated logic for getting table alias

## 1.0.2.2-alpha, October 8, 2015

- Changed ActiveQuery::EVENT_BEFORE_PREPARE_STATEMENT to run only for models with HistoryBehavior  

## 1.0.2.1-alpha, September 29, 2015

- Change for HistoryBehavior in setRevision() method where now all attributes are set (previously were set safe attributes only). This change also causes that validators are not attached into model object.
- Added missing document name on history overlay

## 1.0.2-alpha, June 8, 2015

- Added Migration with Status and Comments fields
- Updates for console application

## 1.0.1-alpha, June 3, 2015

- Added BootstrapInterface
- Used Revision::Status to indicate removed items


## 1.0.0-alpha, May 18, 2015

- Initial release.
