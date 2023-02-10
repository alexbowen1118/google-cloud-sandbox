import React, {useEffect, useState} from 'react'
import Chart from "react-apexcharts";
import {useTheme} from "@mui/material";
import {tokens} from "../../theme";

const BarChart = ({current, title, data}) => {
    const theme = useTheme();
    const colors = tokens(theme.palette.mode);
    console.log("Inside Bar Component", data)
    const state = {
        series: [{
            data: data
        }],
        options: {
            chart: {
                type: 'bar',
                height: 170,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    barHeight: '100%',
                    distributed: true,
                    horizontal: true,
                    dataLabels: {
                        position: 'bottom'
                    },
                    align: 'left'
                }
            },
            colors: ['#C8D9C3', '#B1CAAA', '#83AA78', '#6D9960'
            ],
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                style: {
                    colors: ['#496741']
                },
                formatter: function (val, opt) {
                    return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val + "                          "
                },
                offsetX: 0,
            },
            xaxis: {
                categories: ['Last Year', '2 Years Ago', '3 Years Ago'
                ],
                labels: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            },
            legend: {
                show: false
            },
            title: {
                text: title,
                align: 'left',
                style: {
                    color: '#004F93',
                    fontFamily: "Myriad Pro"
                },
                floating: true
            },
        },


    };

    return (
        <div>
            <div id="mixed-chart" style={{marginTop: 18,justifyContent: "space-between"}}>
                <Chart options={state.options} series={state.series} type="bar" height={150} width={495} />
            </div>
        </div>
    )
}

export default BarChart;
