UPDATE specimen SET date_collected = substring(ts_collected,1,10) WHERE date_collected = '0000-00-00';
