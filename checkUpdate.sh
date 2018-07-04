echo "Fail"
cat UpdateZone.log | grep "FAIL" | wc -l
echo "WellDone"
cat UpdateZone.log | grep "WellDone" | wc -l
echo "Nothing"
cat UpdateZone.log | grep "Nothing" | wc -l
