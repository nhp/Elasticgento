# Elasticsearch for Magento

A Magento module to use Elasticsearch as Flat Table Replacement for catalog and search.

Please consider this a minimal working beta version. Currently supports indexing and searching of simple products.

## Installation

Use [modman](https://github.com/colinmollenhour/modman) to install the module:
```
modman clone git@github.com:dng-dev/Elasticgento.git
```


You maybe need to add the autoloading for Elastica

```xml
<config>
    <global>
        <psr0_namespaces>
            <Elastica />
        </psr0_namespaces>
    </global>
</config>
```

## Release Notes

```
Requires Hackathon_PSR0Autoloader
```
[![Analytics](https://ga-beacon.appspot.com/UA-50601392-1/dng-dev/Elasticgento)](https://github.com/dng-dev/Elasticgento)