import './App.css';
import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import {CssBaseline, Switch, ThemeProvider} from "@mui/material";
import { useDefaultTheme } from './theme';
import LoginPage from "./Components/Auth/LoginPage";
import Dashboard from "./Components/Dashboard";
import ViewDevicesPage from "./Components/Devices/ViewDevicesPage";
import ViewDevicePage from "./Components/Devices/ViewDevicePage";
import AddDevicePage from "./Components/Devices/AddDevicePage";
import EditDevicePage from "./Components/Devices/EditDevicePage";
import ViewDataPage from "./Components/VisitationData/ViewAllDataPage";
import EditDataPage from "./Components/VisitationData/EditDataPage";
import AddDataPage from "./Components/VisitationData/AddDataPage";
import ViewAllDataPage from "./Components/VisitationData/ViewAllDataPage";
import AddMultiplierRule from "./Components/Devices/AddMultiplierRule";
import EditMultiplierRule from "./Components/Devices/EditMultiplierRule";
import ProSidebar from "./global/ProSidebar";
import {ProSidebarProvider} from "react-pro-sidebar";

function App() {
    const theme = useDefaultTheme();
    let pathName = '/visitation';
    return (
        <ThemeProvider theme={theme}>
            <CssBaseline />
            <Router basename={'/visitation'}>
                <div className="app" >
                    <ProSidebar />
                    <main className="content">
                        <Routes>
                            <Route path='/' element={<LoginPage />} />
                            <Route path='/MainLandingPage' element={<Dashboard />} />
                            {/*Device Pages*/}
                            <Route path='/ViewDevicesPage' element={<ViewDevicesPage />} />
                            <Route path='/ViewDevicePage' element={<ViewDevicePage />} />
                            <Route path='/AddDevicePage' element={<AddDevicePage />} />
                            <Route path='/EditDevicePage' element={<EditDevicePage />} />
                            <Route path='/AddMultiplierRule' element={<AddMultiplierRule />} />
                            <Route path='/EditMultiplierRule' element={<EditMultiplierRule />} />
                            {/*Data Pages*/}
                            <Route path='/ViewDataPage' element={<ViewDataPage />} />
                            <Route path='/EditDataPage' element={<EditDataPage />} />
                            <Route path='/AddDataPage' element={<AddDataPage />} />
                            <Route path='/ViewAllDataPage' element={<ViewAllDataPage />} />
                        </Routes>
                    </main>
                </div>
                </Router>

        </ThemeProvider>
    );
}

export default App;
