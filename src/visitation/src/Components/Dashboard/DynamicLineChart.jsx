import React, {useEffect, useState} from 'react'
import Chart from "react-apexcharts";
import {useTheme} from "@mui/material";
import {tokens} from "../../theme";

const DynamicLineChart = ({data, min, max}) => {
    const theme = useTheme();
    const colors = tokens(theme.palette.mode);
    console.log("Inside Graph Component", data)
    let ymax = Math.max(data.y)
    const state = {

        series: [{
            data: data
        }],
        options: {
            chart: {
                id: 'line-datetime',
                type: 'line',

                zoom: {
                    autoScaleYaxis: true
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
                }
            },
            dataLabels: {
                enabled: false
            },
            markers: {
                hover: {
                    size: undefined,
                    sizeOffset: 5
                },
                colors: [colors.parksgreen1[900]],
                strokeColors: [colors.parksgreen1[900]],
            },
            xaxis: {
                type: 'datetime',
                min: min.getTime(),
                max: max.getTime()
            },
            yaxis: {
                forceNiceScale: true,
            },
            colors: [colors.parksgreen1[900]],
            stroke: {
                curve: 'smooth',
            },
            noData: {
                text: "Loading . . .",
                align: 'center',
                verticalAlign: 'middle',
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: colors.parksgreen1[900],
                    fontSize: '28px',
                }
            }
        }
    };


    return (
        <div style={{marginRight:20}}>
            <div id="chart-timeline" style={{justifyContent: "space-between"}}>
                <Chart options={state.options} series={state.series} type="line" height={345}/>
            </div>
        </div>
    );
}

export default DynamicLineChart;
