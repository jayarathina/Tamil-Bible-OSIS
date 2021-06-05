# Tamil-Bible-OSIS
Generates Tamil Catholic Bible in [OSIS format](http://crosswire.org/osis/). This project uses [Tamil-Bible-Database](https://github.com/jayarathina/Tamil-Bible-Database) as source for data.

The aim is to generate OSIS format to aid the creation of a [A SWORD module](https://www.crosswire.org/sword/develop/swordmodule/)

The download contains OSIS file and SWORD module. This can be used in any bible application that supports A SWORD module. We recomend [AndBible](https://andbible.github.io) for Android. A list of applications that support SWORD Modules can be found [here](https://www.crosswire.org/applications/).

## Features
* Poetic Verse divisions
* Red Letter Encoded

## Future
* Footnotes and Cross reference support

## Note:
* This project does NOT have libraries to convert OSIS into a SWORD module. Only the result is placed [here](Output/TAMCT-CE.zip). To know more about converting see: [Osis2mod](https://wiki.crosswire.org/Osis2mod) Library.
* To complie OSIS from scratch, first set up MySQL DB from [Tamil-Bible-Database](https://github.com/jayarathina/Tamil-Bible-Database) before running this file.