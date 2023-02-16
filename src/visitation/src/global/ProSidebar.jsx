import { useState } from "react";
import {Menu, MenuItem, useProSidebar, Sidebar} from "react-pro-sidebar";
import { Box, IconButton, Typography, useTheme } from "@mui/material";
import {Link, useNavigate} from "react-router-dom";
import { tokens } from "../theme";
import PeopleOutlinedIcon from "@mui/icons-material/PeopleOutlined";
import MenuOutlinedIcon from "@mui/icons-material/MenuOutlined";
import SsidChartIcon from '@mui/icons-material/SsidChart';
import AodIcon from '@mui/icons-material/Aod';

const ProSidebar = () => {
    const theme = useTheme();
    const navigate = useNavigate();
    const colors = tokens(theme.palette.mode);
    const [selected, setSelected] = useState("Dashboard");
    const { collapseSidebar, toggleSidebar, collapsed, toggled, broken, rtl } = useProSidebar();


    return (
        // <Box
        //     sx={{
        //         bgcolor: colors.parksblue[50],
        //         boxShadow: 1,
        //         display: 'flex',
        //         height: '100%',
        //         width: '20%'
        //     }}
        // >
                <div style={{ display: 'flex', height: '100%', position:"sticky" }}>
                    <Sidebar defaultCollapsed={true} height = '100%' backgroundColor={colors.parksgreen1[500]}>
                        <Menu>
                            {/* LOGO AND MENU ICON */}
                            <MenuItem
                                onClick={() => collapseSidebar(!collapsed)}
                                style={{
                                    justifyContent: "space-between",
                                    alignItems: "right",
                                    color: colors.parksblue[800],
                                }}
                            >
                                {!collapsed && (
                                    <Box
                                        display="flex"
                                        justifyContent="space-between"
                                        alignItems="right"
                                        alignContent="right"
                                        textAlign="right"
                                    >
                                        <IconButton onClick={() => collapseSidebar(!collapsed)}>
                                            <MenuOutlinedIcon />
                                        </IconButton>
                                    </Box>
                                )}
                                {collapsed && (
                                    <Box
                                        display="flex"
                                        justifyContent="space-between"
                                        alignItems="center"
                                        alignContent="center"
                                    >
                                        <IconButton onClick={() => collapseSidebar()}>
                                            <MenuOutlinedIcon />
                                        </IconButton>
                                    </Box>
                                )}

                            </MenuItem>

                                <Box mb="85px" mt = "50px">
                                    {/*<Box display="flex" justifyContent="center" alignItems="center">*/}
                                    {/*    <img*/}
                                    {/*        alt="logo"*/}
                                    {/*        src={'visitation/src/styling/logo.png'}*/}
                                    {/*        style={{ cursor: "pointer", borderRadius: "50%" }}*/}
                                    {/*    />*/}
                                    {/*</Box>*/}
                                    {!collapsed && (
                                    <Box textAlign="center">
                                        <Typography
                                            variant="h3"
                                            color={colors.parksblue[900]}
                                            fontStyle={"italic"}
                                        >
                                            Naturally<br/>Wonderful
                                        </Typography>
                                    </Box>
                                    )}
                                    {collapsed && (
                                        <Box textAlign="center">
                                            <Typography
                                                variant="h3"
                                                color={colors.parksblue[900]}
                                                fontStyle={"italic"}
                                            >
                                                <br/><br/>
                                            </Typography>
                                        </Box>
                                    )}

                                </Box>


                                <Box  mb="75px" >
                                    <MenuItem
                                        onClick={() => {
                                            navigate("/MainLandingPage")
                                            setSelected("Dashboard");
                                        }}
                                        active={selected === "Dashboard"}
                                        style={{
                                            color: colors.parksblue[800],
                                            alignItems: "left"
                                        }}
                                        icon={<SsidChartIcon />}
                                    >
                                        <Typography>Dashboard</Typography>
                                    </MenuItem>
                                </Box>
                                <Box  mb="75px" >
                                    <MenuItem
                                        onClick={() => {
                                            navigate("/ViewDevicesPage")
                                            setSelected("Devices");
                                        }}
                                        active={selected === "Devices"}
                                        style={{
                                            color: colors.parksblue[800],
                                            alignItems: "left"
                                        }}
                                        icon={<AodIcon />}
                                    >
                                        <Typography>Devices</Typography>
                                    </MenuItem>
                                </Box>
                                <Box  mb="75px" >
                                    <MenuItem
                                        onClick={() => {
                                            navigate("/ViewAllDataPage");
                                            setSelected("Visitation Data");
                                        }}
                                        active={selected === "Visitation Data"}
                                        style={{
                                            color: colors.parksblue[800],
                                            alignItems: "left"
                                        }}
                                        icon={<PeopleOutlinedIcon />}
                                    >
                                        <Typography>Visitation Data</Typography>
                                    </MenuItem>
                                </Box>

                        </Menu>
                    </Sidebar>
                </div>
        //</Box>
    );
};

export default ProSidebar;