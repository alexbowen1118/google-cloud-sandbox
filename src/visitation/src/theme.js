
import { createTheme } from "@mui/material/styles";

//color design tokens
export const tokens = () => ({
    'parksgreen1': {
        DEFAULT: '#DFE9DC',
        '50': '#F4F8F3',
        '100': '#EDF3EC',
        '200': '#DFE9DC',
        '300': '#C8D9C3',
        '400': '#B1CAAA',
        '500': '#9ABA91',
        '600': '#83AA78',
        '700': '#6D9960',
        '800': '#5B8050',
        '900': '#496741'
    },
    'parksblue': {
        DEFAULT: '#005DAC',
        '50': '#53B0FF',
        '100': '#46AAFF',
        '200': '#2D9EFF',
        '300': '#1393FF',
        '400': '#0086F9',
        '500': '#0079DF',
        '600': '#006BC6',
        '700': '#005DAC',
        '800': '#004F93',
        '900': '#004179'

    }
});

//mui theme
export const useDefaultTheme = () => {
    const colors = tokens();

    return createTheme({
        palette: {
            primary: {
                main: colors.parksgreen1[400]
            },
            secondary: {
                main: colors.parksblue[700]
            },
            neutral: {
                main: colors.parksgreen1[300]
            },
            background: {
                default: colors.parksgreen1[200]
            }
        },
        typography: {
            fontFamily: "Myriad Pro",
            fontSize: 12,
            h1: {
                fontFamily: "Myriad Pro",
                fontSize: 40,
            },
            h2: {
                fontFamily: "Myriad Pro",
                fontSize: 32,
            },
            h3: {
                fontFamily: "Myriad Pro",
                fontSize: 24,
            },
            h4: {
                fontFamily: "Myriad Pro",
                fontSize: 20,
            },
            h5: {
                fontFamily: "Myriad Pro",
                fontSize: 16,

            },
            h6: {
                fontFamily: "Myriad Pro",
                fontSize: 14,
            }
        }
    });
};