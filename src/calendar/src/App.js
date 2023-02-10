import React from 'react';
import LoginPage from './LoginPage.js';
import AdminInstructorPage from './CreateOrEditInstructor/AdminInstructorPage.js';
import CreateOrEditCourse from './CreateOrEditCourse/CreateOrEditCourse.js'
import AdminLandingPage from './AdminOverview/AdminLandingPage.js'
import CreateOrEditSection from './CreateOrEditSection/CreateOrEditSection.js';
import InstructorLandingPage from './InstructorOverview/InstructorLandingPage.js';
import ManageRoster from './ManageRoster/ManageRoster.js'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import AttendeeLandingPage from './AttendeeOverview/AttendeeLandingPage.js';
import EnrollmentForm from './EnrollmentForm/EnrollmentForm.js';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';

function App() {
  return (
    <LocalizationProvider dateAdapter={AdapterDateFns}>
      <Router basename={'/calendar'}>
        <div className="App">
          <Routes basename={'/calendar'}>
            <Route path='/' element={<LoginPage />} />
            <Route path='/AdminInstructorPage' element={<AdminInstructorPage />} />
            <Route exact path='/CreateCourse' element={<CreateOrEditCourse />} />
            <Route exact path='/EditCourse/:id' element={<CreateOrEditCourse />} />
            <Route exact path='/CreateOrEditSection' element={<CreateOrEditSection admin={true} />} />
            <Route exact path='CreateOrEditSection/:instructorId' element={<CreateOrEditSection admin={false} />} />
            <Route path='/AdminLandingPage' element={<AdminLandingPage />} />
            <Route exact path='/EnrollmentForm/:userId' element={<EnrollmentForm />} />
            <Route path='/InstructorLandingPage' element={<InstructorLandingPage />} />
            <Route path='/AttendeeLandingPage' element={<AttendeeLandingPage />} />
            <Route path='/ManageRoster' element={<ManageRoster admin={true} />} />
            <Route path='/ManageRoster/:instructorId' element={<ManageRoster admin={false} />} />
          </Routes>
        </div>
      </Router>
    </LocalizationProvider>
  );
}

export default App;
