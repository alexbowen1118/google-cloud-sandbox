import React, {useEffect, useState} from "react";
import Button from "@mui/material/Button";
import TextField from "@mui/material/TextField";
import {ButtonGroup, Typography} from "@mui/material";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import APIClient from "../../utils/APIClient";
import {useNavigate} from "react-router-dom";
import {useSearchParams} from "react-router-dom";
import { useLocation } from 'react-router-dom';


function EditMultiplierRule(props) {

    const navigate = useNavigate();
    const { state } = useLocation();

    if (state == null || state.back == null) {
        window.location = "MainLandingPage";
    }

    const counterRule = state.counterRule;
    const device = state.device;

    console.log(counterRule)

    const [rul_dev_id, rul_start, rul_end, rul_multiplier]=React.useState('');
    // let oldstart = new Date(counterRule.start);
    // let oldend = new Date(counterRule.end);

    const [counter_rule] = useState(props.counter_rule ? {...props.counter_rule} : {
        rul_dev_id,
        rul_start,
        rul_end,
        rul_multiplier
    });

    counter_rule.rul_multiplier = counterRule.multiplier;

    // let [startDate, setStartDate] = useState('');
    const [startDate, setStartDate] = useState(new Date(counterRule.start));
    const [endDate, setEndDate] = useState(new Date(counterRule.end));

    // let [endDate, setEndDate] = useState('');

    function onChangeStartHandler(value) {
        setStartDate(value);
        // oldstart = new Date(value)
    }

    function onChangeEndHandler(value) {
        setEndDate(value);
        // oldend = new Date(value)

    }

    const handleMult = (event) => {
        counter_rule.rul_multiplier = event.target.value
    };

    function submitCounterRule() {
        counter_rule.rul_dev_id = counterRule.dev_id

        if (startDate === '') {
            counter_rule.rul_start = counterRule.start
        } else {
            let startpart1 = JSON.stringify(startDate).substring(1, 11)
            let startpart2 = JSON.stringify(startDate).substring(13, 20)
            const finalstart = startpart1 + " " + startpart2

            counter_rule.rul_start = finalstart;

        }
        if ( endDate === '') {
            counter_rule.rul_end = counterRule.end
        } else {
            let endpart1 = JSON.stringify(endDate).substring(1, 11)
            let endpart2 = JSON.stringify(endDate).substring(13, 20)
            const finalend = endpart1 + " "+ endpart2


            counter_rule.rul_end = finalend;
        }

        APIClient.CounterRules.updateCounterRules(counter_rule.rul_dev_id, counter_rule, counterRule.id).then(r => console.log("success"));
    }

    function goBack() {
        console.log("Here2");
        let back = "/ViewDevicesPage";
        navigate('/ViewDevicePage',
            {
                state: {device, back}
            });
        window.location = "ViewDevicesPage";
    }

    function deleteRule() {
        APIClient.CounterRules.deleteCounterRules(counterRule.dev_id, counterRule.id).then(r => console.log("success"));
       goBack();
    }

    // Hides elements because of permissions
    // Permissions:
    // 0	NoAccess
    // 1	BaseLevel
    // 2	Manager
    // 3	Admin
    // 4	SuperAdmin
    let role = sessionStorage.getItem("role")
    if(role !== "Admin" && role !== "SuperAdmin" ){
        goBack();
        return null;
    }


    return (
        <div className='editMultiplierRulePage'>
            <div className="pageHeader">
                <h2 > Edit Multiplier Rule</h2>
            </div>
            <div className="btn-toolbar">
                <Button onClick={() => {goBack()}} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Back</Button>
                <Button onClick={() => {deleteRule()}} variant="outlined" style={{ borderColor: '#000000',  color:'#000000' }}>Delete Rule</Button>
            </div>
            <p> </p>
            <TextField required id="multiplier" label="Enter Multiplier" onChange={handleMult} defaultValue={counterRule.multiplier} />
            <p> </p>
            <b>Start Date</b>
            <div>
                {/*<DatePicker*/}
                {/*    defaultValue={oldstart}*/}
                {/*    onChange={(date) => onChangeStartHandler(date)}*/}
                {/*    dateFormat="yyyy-MM-dd hh:mm:ss"*/}
                {/*/>*/}
                {/*<DatePicker*/}
                {/*    defaultvalue={oldend}*/}
                {/*    onChange={(date) => onChangeEndHandler(date)}*/}
                {/*    dateFormat="yyyy-MM-dd hh:mm:ss"*/}
                {/*/>*/}
                <DatePicker
                    selected={startDate}
                    onChange={(date) => setStartDate(date)}
                    // showTimeSelect
                    // timeFormat="HH:mm"
                    // timeIntervals={15}
                    // timeCaption="time"
                    dateFormat="MM-dd"
                />
                <p> </p>

                <b>End Date</b>
                <DatePicker
                    selected={endDate}
                    onChange={(date) => setEndDate(date)}
                    // showTimeSelect
                    // timeFormat="HH:mm"
                    // timeIntervals={15}
                    // timeCaption="time"
                    dateFormat="MM-dd"
                />
            </div>
            <p> </p>
            <Button variant="outlined" onClick={submitCounterRule} style={{ borderColor: '#000000',  color:'#000000' }}>Save</Button>
        </div>
    );

}

export default EditMultiplierRule;