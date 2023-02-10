import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { render, screen, fireEvent } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import AdminInstructorPage from './AdminInstructorPage';
import AdminLandingPage from '../AdminOverview/AdminLandingPage.js'

test('all labels are displayed', () => {
  render(<Router><CreateOrEditCourse /></Router>);

  const title = screen.getByText('Title:');
  const first = screen.getByText('First Name:');
  const last = screen.getByText('Last Name:');
  const addr1 = screen.getByText('Address Line 1:');
  const addr2 = screen.getByText('Address Line 2:');
  const city = screen.getByText('City:');
  const state = screen.getByText('State:');
  const zip = screen.getByText('ZIP Code:');
  const phone = screen.getByText('Phone Number:');
  const fax = screen.getByText('Fax:');
  const email = screen.getByText('Email:');
  const website = screen.getByText('Website:');

  expect(title).toBeInTheDocument();
  expect(first).toBeInTheDocument();
  expect(last).toBeInTheDocument();
  expect(addr1).toBeInTheDocument();
  expect(addr2).toBeInTheDocument();
  expect(city).toBeInTheDocument();
  expect(state).toBeInTheDocument();
  expect(zip).toBeInTheDocument();
  expect(phone).toBeInTheDocument();
  expect(fax).toBeInTheDocument();
  expect(email).toBeInTheDocument();
  expect(website).toBeInTheDocument();
});

test('submit and exit buttons are displayed', () => {
    render(<Router><CreateOrEditCourse /></Router>);
  
    const addInstructor = screen.getByText('Add Instructor');
    const home = screen.getByText('Home');
  
    expect(addInstructor).toBeInTheDocument();
    expect(home).toBeInTheDocument();
});

/**
test('submit button works', () => {
    render(
    <Router>
        <Routes>
            <Route path='/AdminLandingPage' element={<AdminLandingPage />} />
        </Routes>
        <CreateOrEditCourse />
    </Router>
    );
  
    const createCourse = screen.getByText('Create Course');
  
    fireEvent.click(createCourse);
    
    expect(screen.getByText('Admin Home Page')).toBeInTheDocument();
});

test('input boxes change when typed in', () => {
    render(<Router><CreateOrEditCourse /></Router>);

    const courseName = screen.getByLabelText('Course Name:');
    const description = screen.getByLabelText('Description:');
    const reqs = screen.getByLabelText('Requirements:');

    fireEvent.change(courseName, {target: {value: 'Fire Safety 101'}});
    expect(courseName.value).toBe('Fire Safety 101');

    fireEvent.change(description, {target: {value: 'Basic fire safety course'}});
    expect(description.value).toBe('Basic fire safety course');

    fireEvent.change(reqs, {target: {value: 'No prereqs for this course'}});
    expect(reqs.value).toBe('No prereqs for this course');

});

test('select tag displays all options', () => {
    render(<Router><CreateOrEditCourse /></Router>);

    const category = screen.getByLabelText('Category:');

    fireEvent.click(category);
    
    const selectCategory = screen.getByText('Select category');
    const safety = screen.getByText('Safety');
    const training = screen.getByText('Training');
    const other = screen.getByText('Other');

    expect(selectCategory).toBeInTheDocument();
    expect(safety).toBeInTheDocument();
    expect(training).toBeInTheDocument();
    expect(other).toBeInTheDocument();
});

test('select tag does not allow you to choose placeholder option', () => {
    render(<Router><CreateOrEditCourse /></Router>);

    const category = screen.getByLabelText('Category:');

    fireEvent.click(category);
    
    const selectCategory = screen.getByText('Select category');
    const safety = screen.getByText('Safety');
    const training = screen.getByText('Training');
    const other = screen.getByText('Other');

    fireEvent.click(selectCategory);

    expect(selectCategory).toBeInTheDocument();
    expect(safety).toBeInTheDocument();
    expect(training).toBeInTheDocument();
    expect(other).toBeInTheDocument();
});

test('select tag does allow you to choose a valid option', () => {
    render(<Router><CreateOrEditCourse /></Router>);

    const category = screen.getByLabelText('Category:');
    
    userEvent.selectOptions(category, 'Safety');

    expect(category).toHaveValue('safety');
});
*/