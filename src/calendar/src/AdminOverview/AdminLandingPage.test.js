import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { render, screen, fireEvent } from '@testing-library/react';
import AdminLandingPage from './AdminLandingPage.js';
import AdminInstructorPage from '../CreateOrEditInstructor/AdminInstructorPage.js'
import CreateOrEditCourse from '../CreateOrEditCourse/CreateOrEditCourse.js'

test('all buttons are displayed', () => {
  render(<Router><AdminLandingPage /></Router>);

  const addCourse = screen.getByText('Manage Courses');
  const addInstructor = screen.getByText('Add Instructor');
  const editInstructor = screen.getByText('Edit Instructor');

  expect(addCourse).toBeInTheDocument();
  expect(addInstructor).toBeInTheDocument();
  expect(editInstructor).toBeInTheDocument();
});

test('addCourse button works as expected', () => {
  render(
  <Router>
    <Routes>
        <Route path='/CreateCourse' element={<CreateOrEditCourse />} />
    </Routes>
    <AdminLandingPage />
  </Router>
  );

  const addCourse = screen.getByText('Manage Courses');

  fireEvent.click(addCourse);

  expect(screen.getByText('Save Course')).toBeInTheDocument();
});

test('addInstructor button works as expected', () => {
  render(
  <Router>
    <Routes>
        <Route path='/AdminInstructorPage' element={<AdminInstructorPage />} />
    </Routes>
    <AdminLandingPage />
  </Router>
  );

  const addInstructor = screen.getByText('Add Instructor');

  fireEvent.click(addInstructor);

  expect(screen.getByText('Add an Instructor')).toBeInTheDocument();
});

test('editInstructor button works as expected', () => {
  render(
  <Router>
    <Routes>
      <Route path='/CreateCourse' element={<CreateOrEditCourse />} />
    </Routes>
    <AdminLandingPage />
  </Router>
  );

  const editInstructor = screen.getByText('Edit Instructor');

  fireEvent.click(editInstructor);

  expect(screen.getByText('Save Course')).toBeInTheDocument();
});

