import {Box, FormControl, InputLabel, MenuItem, Select, Typography, useTheme} from "@mui/material";
import { tokens } from "../../theme";
import DynamicLineChart from "./DynamicLineChart";
import React, {useEffect, useState} from "react";
import APIClient from "../../utils/APIClient";
import Header from "../../global/Header";
import DateRangePicker from "rsuite/DateRangePicker";
import {subDays} from "rsuite/cjs/utils/dateUtils";
import './index.css';
import BarChart from "./BarChart";
import ComparisonBarChart from "./ComparisonBarChart";
import {afterToday, allowedMaxDays, combine} from "rsuite/cjs/DateRangePicker/disabledDateUtils";

const Dashboard = () => {
    const theme = useTheme();
    const colors = tokens(theme.palette.mode);
    const [isLoaded, setLoaded] = useState(false);
    const [isHourly, setHourly] = useState(false);
    const [visits,setVisits]=useState([])
    const [parks,setParks]=useState([])
    const [par_id, setCurrentPark]=useState(284/**sessionStorage.getItem("park")*/)

    // entire year counts
    const [ytdTotalRange, setYTDRange]=useState([new Date(new Date().getFullYear(), 0, 1), new Date()])
    const [ytdTotal, setYTDTotal]=useState(0)
    const [oneYrAgoAllTotalRange, setOneYrAgoAllTotalRange]=useState([new Date(new Date().getFullYear() - 1, 0, 1), new Date(new Date().getFullYear(), 0, 0)])
    const [oneYrAgoAllTotal, setOneYrAgoAllTotal]=useState(0)
    const [twoYrAgoAllTotalRange, setTwoYrAgoAllTotalRange]=useState([new Date(new Date().getFullYear() - 2, 0, 1), new Date(new Date().getFullYear() - 1, 0, 1)])
    const [twoYrAgoAllTotal, setTwoYrAgoAllTotal]=useState(0)
    const [threeYrAgoAllTotalRange, setThreeYrAgoAllTotalRange]=useState([new Date(new Date().getFullYear() - 3, 0, 1), new Date(new Date().getFullYear()-2, 0, 1)])
    const [threeYrAgoAllTotal, setThreeYrAgoAllTotal]=useState(0)

    // selected range
    const [range, setCurrentRange]=useState([subDays(new Date(), 29), new Date()])
    const [rangeTotal, setCurrentRangeTotal]=useState(0)
    // totals for same range in previous years
    const [oneYrAgoRangeTotal, setOneYrAgoRangeTotal]=useState(0)
    const [twoYrAgoRangeTotal, setTwoYrAgoRangeTotal]=useState(0)
    const [threeYrAgoRangeTotal, setThreeYrAgoRangeTotal]=useState(0)
    // all time total
    const [allTimeTotal, setAllTimeTotal]=useState(0)

    const predefinedRanges = [
        {
            label: 'Last 24 hours',
            value: [subDays(new Date(), 1), new Date()],
            placement: 'left'
        },
        // {
        //     label: 'Yesterday',
        //     value: [addDays(new Date(), -1), addDays(new Date(), -1)],
        //     placement: 'left'
        // },
        // {
        //     label: 'This week',
        //     value: [startOfWeek(new Date()), endOfWeek(new Date())],
        //     placement: 'left'
        // },
        {
            label: 'Last 7 days',
            value: [subDays(new Date(), 6), new Date()],
            placement: 'left'
        },
        {
            label: 'Last 30 days',
            value: [subDays(new Date(), 29), new Date()],
            placement: 'left'
        },
        // {
        //     label: 'Last month',
        //     value: [startOfMonth(addMonths(new Date(), -1)), endOfMonth(addMonths(new Date(), -1))],
        //     placement: 'left'
        // },
        {
            label: 'Year to date',
            value: [new Date(new Date().getFullYear(), 0, 1), new Date()],
            placement: 'left'
        },
        // {
        //     label: 'Last year',
        //     value: [new Date(new Date().getFullYear() - 1, 0, 1), new Date(new Date().getFullYear(), 0, 0)],
        //     placement: 'left'
        // },
        // {
        //     label: 'All time',
        //     value: [new Date(new Date().getFullYear() - 1, 0, 1), new Date()],
        //     placement: 'left'
        // },
        // {
        //     label: 'Last week',
        //     closeOverlay: false,
        //     value: value => {
        //         const [start = new Date()] = value || [];
        //         return [
        //             addDays(startOfWeek(start, { weekStartsOn: 0 }), -7),
        //             addDays(endOfWeek(start, { weekStartsOn: 0 }), -7)
        //         ];
        //     },
        //     appearance: 'default'
        // },
        // {
        //     label: 'Next week',
        //     closeOverlay: false,
        //     value: value => {
        //         const [start = new Date()] = value || [];
        //         return [
        //             addDays(startOfWeek(start, { weekStartsOn: 0 }), 7),
        //             addDays(endOfWeek(start, { weekStartsOn: 0 }), 7)
        //         ];
        //     },
        //     appearance: 'default'
        // }
    ];

    useEffect(() => {
        APIClient.Parks.getParks().then(r => {
            setParks(r.map((park) => ({
                    name: park.name,
                    id: park.id
                })).sort(function (a, b) {
                    return a.id - b.id;
                })
            );
        });

        //console.log(JSON.stringify(body))
        APIClient.Visits.getDayVisitsByPark(par_id).then(r => {
            let result = r.map((visit) => ({
                x: new Date(visit.timestamp).getTime(),
                y: parseInt(visit.count_calculated)
            })).sort(function(a,b){
                return a.x - b.x;
            })
            console.log(result)

            if(isHourly) {
                APIClient.Visits.getVisitsByPark(par_id).then(r2 => {
                    let result2 = r2.map((visit) => ({
                        x: new Date(visit.timestamp).getTime(),
                        y: parseInt(visit.count_calculated)
                    })).sort(function (a, b) {
                        return a.x - b.x;
                    })
                    // selected range
                    let trimmed_range = result2.filter(function (x) {
                        return x.x >= range[0].getTime() && x.x <= range[1].getTime()
                    })
                    setVisits(trimmed_range)

                })
            } else {
                setVisits(result);
            }

             if(!isLoaded) {
                 // STATIC RANGES DATA
                 // year to date
                 let trimmed_ytd = result.filter(function (x) {
                     return x.x >= ytdTotalRange[0].getTime() && x.x <= ytdTotalRange[1].getTime()
                 })
                 // whole year one year ago
                 let trimmed_oneyrago = result.filter(function (x) {
                     return x.x >= oneYrAgoAllTotalRange[0].getTime() && x.x <= oneYrAgoAllTotalRange[1].getTime()
                 })
                 // whole year two years ago
                 let trimmed_twoyrago = result.filter(function (x) {
                     return x.x >= twoYrAgoAllTotalRange[0].getTime() && x.x <= twoYrAgoAllTotalRange[1].getTime()
                 })
                 // whole year three years ago
                 let trimmed_threeyrago = result.filter(function (x) {
                     return x.x >= threeYrAgoAllTotalRange[0].getTime() && x.x <= threeYrAgoAllTotalRange[1].getTime()
                 })
                 // SELECTED RANGES DATA
                 // selected range
                 let trimmed_range = result.filter(function (x) {
                     return x.x >= range[0].getTime() && x.x <= range[1].getTime()
                 })
                 // same range last year
                 let trimmed_range_oneyrago = result.filter(function (x) {
                     return x.x >= subtractYears(1, range[0]).getTime() && x.x <= subtractYears(1, range[1]).getTime()
                 })
                 // same range two years ago
                 let trimmed_range_twoyrago = result.filter(function (x) {
                     return x.x >= subtractYears(2, range[0]).getTime() && x.x <= subtractYears(2, range[1]).getTime()
                 })
                  // same range three years ago
                  let trimmed_range_threeyrago = result.filter(function (x) {
                      return x.x >= subtractYears(3, range[0]).getTime() && x.x <= subtractYears(3, range[1]).getTime()
                  })

                 // TOTAL VISITOR CALCULATIONS
                 // STATIC RANGES TOTALS
                 // all time visitor total
                 let total_all = 0;
                 for (let i = 0; i < result.length; i++) {
                     if (result[i].y)
                         total_all += result[i].y;
                 }
                 setAllTimeTotal(total_all)
                 // total visitors year to date
                 let total_ytd = 0;
                 for (let i = 0; i < trimmed_ytd.length; i++) {
                     if (trimmed_ytd[i].y)
                         total_ytd += trimmed_ytd[i].y;
                 }
                 setYTDTotal(total_ytd)
                 // total visitors in previous year
                 let total_oneyrago = 0;
                 for (let i = 0; i < trimmed_oneyrago.length; i++) {
                     if (trimmed_oneyrago[i].y)
                         total_oneyrago += trimmed_oneyrago[i].y;
                 }
                 setOneYrAgoAllTotal(total_oneyrago)
                 // total visitors two years ago
                 let total_twoyrago = 0;
                 for (let i = 0; i < trimmed_twoyrago.length; i++) {
                     if (trimmed_twoyrago[i].y)
                         total_twoyrago += trimmed_twoyrago[i].y;
                 }
                 setTwoYrAgoAllTotal(total_twoyrago)
                 // total visitors three years ago
                 let total_threeyrago = 0;
                 for (let i = 0; i < trimmed_threeyrago.length; i++) {
                     if (trimmed_threeyrago[i].y)
                         total_threeyrago += trimmed_threeyrago[i].y;
                 }
                 setThreeYrAgoAllTotal(total_threeyrago)

                 // SELECTED RANGE TOTALS
                 let total_range = 0;
                 for (let i = 0; i < trimmed_range.length; i++) {
                     if (trimmed_range[i].y)
                         total_range += trimmed_range[i].y;
                 }
                 setCurrentRangeTotal(total_range)

                 //total visitors in same range 1 yr ago
                 let total_oneyrago_range = 0;
                 for (let i = 0; i < trimmed_range_oneyrago.length; i++) {
                     if (trimmed_range_oneyrago[i].y)
                         total_oneyrago_range += trimmed_range_oneyrago[i].y;
                 }
                 setOneYrAgoRangeTotal(total_oneyrago_range)

                 //total visitors in same range 2 yrs ago
                 let total_twoyrago_range = 0;
                 for (let i = 0; i < trimmed_range_twoyrago.length; i++) {
                     if (trimmed_range_twoyrago[i].y)
                         total_twoyrago_range += trimmed_range_twoyrago[i].y;
                 }
                 setTwoYrAgoRangeTotal(total_twoyrago_range)

                 //total visitors in same range 3 yrs ago
                 let total_threeyrago_range = 0;
                 for (let i = 0; i < trimmed_range_threeyrago.length; i++) {
                     if (trimmed_range_threeyrago[i].y)
                         total_threeyrago_range += trimmed_range_threeyrago[i].y;
                 }
                 setThreeYrAgoRangeTotal(total_threeyrago_range)

             }
        });

    }, [par_id, range]);

    function updatePark(event) {
        setVisits([])
        setCurrentPark(event.target.value);
    }
    async function updateRange(event) {
        setVisits([])
        let dtime = event[1] - event[0];
        let dhours = dtime / (1000 * 3600);
        console.log("dhours",dhours)
        //three days will show hourly
        if(dhours <= 96){
            setHourly(true)
        }
        await setCurrentRange(event)
        setHourly(false)
    }

    function subtractYears(numOfYears, date) {
        let temp = new Date(date)
        temp.setFullYear(date.getFullYear() - numOfYears);
        return temp;
    }

    return (
        <Box m="20px">

            {/* GRID & CHARTS */}
            <Box
                display="grid"
                gridTemplateColumns="repeat(12, 1fr)"
                gridAutoRows="70px"
                gap="25px"
            >
                {/* HEADER AND DATE/PARK SELECTION */}
                <Box
                    gridColumn="span 3"
                    display="flex"
                    justifyContent="left"
                >
                    <Header title="Visitation Dashboard" subtitle="Naturally Wonderful" style={{display: "flex", float: "left"}}/>
                </Box>
                <Box
                    gridColumn="span 2"
                    gridRow="span 1"
                    display="flex"
                    alignItems="center"
                    justifyContent="left"
                >

                    <FormControl>
                        <InputLabel
                            id="park-select-label"
                        >
                            Park
                        </InputLabel>
                        <Select
                            labelId="park-select-label"
                            id="park-select"
                            label="Park"
                            value={par_id}
                            onChange={updatePark}
                            style={{display:"flex"}}
                        >
                            {parks.map((park) => (
                                <MenuItem
                                    key={park.name}
                                    value={park.id}
                                >
                                    {park.name}
                                </MenuItem>
                            ))}
                        </Select>
                    </FormControl>
                </Box>
                <Box
                    gridColumn="span 2"
                    gridRow="span 1"
                    display="flex"
                    alignItems="center"
                    justifyContent="left"
                >
                    <DateRangePicker
                        ranges={predefinedRanges}
                        placeholder="Select dates to view"
                        style={{display:"flex", width: 250}}
                        value={range}
                        onChange={updateRange}
                        disabledDate={combine(allowedMaxDays(365), afterToday())}
                    />
                </Box>

                {/* ROW 2 */}
                <Box
                    gridColumn="span 12"
                    gridRow="span 5"
                    backgroundColor={colors.parksgreen1[500]}
                >
                    <Box
                        mt="20px"
                        p="0 30px"
                        display="flex "
                        justifyContent="space-between"
                        alignItems="center"
                    >
                        <Box>
                            <Typography
                                variant="h4"
                                fontWeight="600"
                                color={colors.parksblue[800]}
                            >
                                Total visitors in date range<br/>
                            </Typography>
                            <Typography
                                variant="h2"
                                fontWeight="bold"
                                color={colors.parksgreen1[900]}
                            >
                                {rangeTotal}
                            </Typography>
                        </Box>
                    </Box>
                    <Box
                        height="300px"
                    >
                        <DynamicLineChart data={visits} min={range[0]} max={range[1]}/>
                    </Box>
                </Box>
                {/* ROW 3 */}
                <Box
                    gridColumn="span 4"
                    gridRow="span 2"
                    display="flex "
                    justifyContent="space-between"
                    alignItems="center"
                    backgroundColor={colors.parksgreen1[500]}
                >
                    <BarChart  title='Total yearly visitors' data={[ytdTotal, oneYrAgoAllTotal, twoYrAgoAllTotal, threeYrAgoAllTotal]} />
                </Box>
                <Box
                    gridColumn="span 4"
                    gridRow="span 2"
                    display="flex "
                    justifyContent="space-between"
                    alignItems="center"
                    backgroundColor={colors.parksgreen1[500]}
                >
                    <ComparisonBarChart title='Compare to the same date range' current={rangeTotal} data={[oneYrAgoRangeTotal, twoYrAgoRangeTotal, threeYrAgoRangeTotal]} />
                </Box>
                <Box
                    gridColumn="span 4"
                    gridRow="span 2"
                    backgroundColor={colors.parksgreen1[500]}
                    display="flex"
                    alignItems="center"
                    justifyContent="center"
                >
                    <Box>
                        <Typography
                            variant="h2"
                            fontWeight="600"
                            color={colors.parksblue[800]}
                        >
                            Total park visitors<br/>
                        </Typography>
                        <Typography
                            variant="h1"
                            fontWeight="bold"
                            color={colors.parksgreen1[900]}
                        >
                            {allTimeTotal}
                        </Typography>
                    </Box>
                </Box>
            </Box>
        </Box>
    );
};

export default Dashboard;