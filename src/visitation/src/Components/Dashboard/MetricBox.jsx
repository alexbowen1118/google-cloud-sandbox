import { Box, Typography, useTheme } from "@mui/material";
import { tokens } from "../../theme";

const MetricBox = ({ title, subtitle, increase }) => {
    const theme = useTheme();
    const colors = tokens(theme.palette.mode);

    return (
        <Box width="100%" m="0 30px">
            <Box display="flex" justifyContent="space-between">
                <Box>
                    <Typography
                        variant="h4"
                        fontWeight="bold"
                        sx={{ color: colors.parksblue[800] }}
                    >
                        {title}
                    </Typography>
                </Box>
            </Box>
            <Box display="flex" justifyContent="space-between" mt="2px">
                <Typography variant="h5" sx={{ color: colors.parksblue[700] }}>
                    {subtitle}
                </Typography>
                <Typography
                    variant="h4"
                    fontStyle="italic"
                    sx={{ color: colors.parksgreen1[900] }}
                >
                    {increase}
                </Typography>
            </Box>
        </Box>
    );
};

export default MetricBox;