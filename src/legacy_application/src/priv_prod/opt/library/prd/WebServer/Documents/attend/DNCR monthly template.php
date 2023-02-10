Calendar Year

SELECT left(`year_month_day`,4) as year, left(park,4) as state_park, substr(`year_month_day`,5,2) as month, sum(attend_tot) as attendance

FROM `stats_day`

where left(`year_month_day`,4)=2016 and substr(`year_month_day`,5,2)<'05'

group by left(park,4), substr(`year_month_day`,5,2)


Fiscal Year
SELECT left(`year_month_day`,4) as year, left(park,4) as state_park, substr(`year_month_day`,5,2) as month, sum(attend_tot) as attendance

FROM `stats_day`

where `year_month_day`>'20140700' and `year_month_day`<20150700

group by left(park,4), substr(`year_month_day`,1,6)