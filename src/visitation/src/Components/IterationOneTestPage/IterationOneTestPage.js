import React from 'react';
import { useNavigate } from 'react-router-dom';
import Button from '@mui/material/Button';

function IterationOneTestPage() {

    const navigate = useNavigate();
    return (
        <div className='IterationOneTestPage'>
            <Button variant="contained">Hello World</Button>;
            <p>
                Iteration One Example:
                <br></br>
                <table>
                    <tr>
                        <th>Timestamp</th>
                        <th>Park ID</th>
                        <th>Device ID</th>
                        <th>Counter</th>
                    </tr>
                    <tr>
                        <td>09/09/2022</td>
                        <td>BAIS</td>
                        <td>Device 1</td>
                        <td>30</td>
                    </tr>
                </table>
            </p>
        </div>
    );
}

export default IterationOneTestPage;