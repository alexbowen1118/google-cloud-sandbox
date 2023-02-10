import React, {useState, useEffect, useRef} from 'react';

import APIClient from '../utils/APIClient';
import ConfigFetcher from '../utils/ConfigFetcher';

export const VisitationContext = React.createContext();

export default function VisitationProvider(props) {
    //const [config, setConfig] = useState();
    //const [initError, setInitError] = useState(false);
    //const [user, setUser] = useState();
    const [devices, setDevices] = useState();

    useEffect(() => {
        async function fetchData() {
            try {
                // TODO: implement
            } catch(error) {
                console.log("VisitationProvider bootstrapping error", error);
            }
        }
        fetchData();
    }, []);

}