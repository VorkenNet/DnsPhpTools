#!/bin/bash
grep $1 VarNamed/pri* | cut -d : -f 2 | cut -f 1 | xargs -n1 dig @8.8.8.8 +noall +answer
