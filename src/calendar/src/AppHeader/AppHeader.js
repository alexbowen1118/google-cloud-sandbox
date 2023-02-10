import React from 'react';
import { useNavigate } from 'react-router-dom';
import { AppBar } from '@mui/material';
import Button from '@mui/material/Button';
import Toolbar from '@mui/material/Toolbar';
import { Typography } from '@mui/material';



function AppHeader(props) {
    const navigate = useNavigate();

    function logout() {
        document.cookie = "jwtHeader=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "jwtPayload=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        navigate("/");
    }

    const headerText = props.headerText;
    return (
        <div className='appHeader'>
            <AppBar sx={{ p: 1, fontSize: 20, backgroundColor: 'darkblue' }}>
                <Toolbar variant='dense'>
                    <Typography sx={{ flex: 1 }} variant="title" color="common.white">{headerText}</Typography>
                    <Button sx={{ width: 1 / 10 }} variant='contained' fullWidth={true} onClick={logout}>
                        Logout
                    </Button>
                </Toolbar>
            </AppBar>
        </div>
    )
}

export default AppHeader;