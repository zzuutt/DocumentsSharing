# 0.4

- fix small bug
- add smarty plugins modifier : filesize
  convert bytes to 'b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb'
  {$number|filesize}  $number= 10000     return-> 10.00Kb