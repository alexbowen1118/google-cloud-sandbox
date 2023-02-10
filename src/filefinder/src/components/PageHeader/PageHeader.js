import React from "react";
import { useState } from "react";
import Toolbar from '@mui/material/Toolbar';
import Drawer from '@mui/material/Drawer';
import IconButton from '@mui/material/IconButton';
import MenuIcon from '@mui/icons-material/Menu';
import { Button } from "@mui/material";
import CloudUploadTwoToneIcon from '@mui/icons-material/CloudUploadTwoTone';
import { useNavigate } from 'react-router-dom';
import { useLocation } from 'react-router-dom'
import './PageHeader.css'


function PageHeader() {
    const navigate = useNavigate();
    const location = useLocation();
    const [isDrawerOpen, setIsDrawerOpen] = useState(false);
    let currentUserRole = window.sessionStorage.getItem("currentUserRole");

    const handelUploadFilesBttn = () => {
        navigate('/uploadfiles');
        setIsDrawerOpen(false);
    }

    const handleAdminActionsBttn = () => {
        navigate('/adminactions');
        setIsDrawerOpen(false);
    }

    const handleSearchBttn = () => {
        navigate('/search');
        setIsDrawerOpen(false);
    }

    const handleLogoutBttn = () => {
        window.sessionStorage.removeItem('currentUserRole');
        window.sessionStorage.removeItem('currentUserId');
        navigate('/');
        setIsDrawerOpen(false);
    }

    return (
        <div className="PageHeaderContainer">
            <div className="MenuBar">
                {location.pathname !== '/' &&
                    <Toolbar>
                            <IconButton
                            size="large"
                            edge="start"
                            color="inherit"
                            aria-label="open drawer"
                            sx={{ mr: 2 }}
                            onClick = {()=> setIsDrawerOpen(true)}
                        >
                            <MenuIcon />
                        </IconButton>
                        <Drawer id = "drawer" open={isDrawerOpen} onClose={()=> setIsDrawerOpen(false)} anchor='left'>
                            {/* <Typography> */}
                                <h3>Menu</h3>
                            {/* </Typography> */}
                            {location.pathname !== '/uploadfiles' &&
                                <Button variant="outlined"  size="large" id="upload-files-bttn" component="label" onClick={handelUploadFilesBttn}>
                                    Upload Files&nbsp;
                                    <CloudUploadTwoToneIcon/>
                                </Button>
                            }
                            {location.pathname !== '/search' &&
                                <Button variant="outlined"  size="large" id="search-files-bttn" component="label" onClick={handleSearchBttn}>
                                    Search&nbsp;
                                </Button>
                            }
                            {(currentUserRole !== "Base" && currentUserRole !== "Manager" && location.pathname !== '/adminactions') &&
                                <Button variant="outlined" size="large" id="admin-actions-bttn" component="label" onClick={handleAdminActionsBttn}>
                                    Admin Actions&nbsp;
                                </Button>                  
                            }
                            <Button variant="outlined"  size="large" id="logout-bttn" component="label" onClick={handleLogoutBttn}>
                                Logout
                            </Button>
                        
                        </Drawer>
                    </Toolbar>
                }
            </div>
            <div className="DPRheading">
                <h1>NC DPR File Finder</h1>
            </div>
            <div className="Photo">
                {/* <img src="https://files.nc.gov/parks/website-logo.png?VersionId=b9OoWDjx8WH3v4C3mtorvt1EpTVPATLZ" alt="NC Parks and Rec logo"/> */}
                <img id="logo" alt="NC Parks and Rec logo"></img>
            </div>
        </div>
    )
}


export default PageHeader;