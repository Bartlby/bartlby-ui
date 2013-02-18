#!/bin/bash
echo -n "Version:";
read n
rm -vfr /tmp/debian-ui/
php make_deb.php $n;
dpkg -b /tmp/debian-ui/ bartlby-ui_$n.deb
echo "DONE DEB: bartlby-ui_$n.deb"
